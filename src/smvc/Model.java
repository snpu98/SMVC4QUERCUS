package smvc;

import java.util.HashMap;
import java.util.Iterator;

public class Model extends Database{
	private HashMap<String, String>	colArr   ;
	private String                  tableName;
	private String                  pkName   ;

	public Model(String _tableName, String _pkVal){
		if(this.read("SHOW COLUMNS FROM " + "`" +_tableName+ "`" + ";")){
			tableName            = _tableName;
			colArr               = new HashMap<String, String>();
			String           key = new String();
			String           val = new String();
			Iterator<String> itr = getRow().keySet().iterator();
			while( itr.hasNext() ){
				key = itr.next();
				val = getRow().get(key);
				if( key=="PRI" ){
					pkName = val;
					colArr.put(val,_pkVal);
				}else{
					colArr.put(val,null);
				}
			}
		}
	}

	public String getPKName(){
		if(pkName==null || pkName==""){
			Log.logWrite("Model->getPKName error: Primary key is empty");
		}
		return pkName;
	}

	public String getPKVal(){
		if(colArr.get(pkName)==null || colArr.get(pkName)==""){
			Log.logWrite("Model->getPKVal error: Primary key value is empty");
		}
		return colArr.get(pkName);
	}

	public HashMap<String, String> getColArr(){
		if(colArr.size()<1){
			Log.logWrite("Model->getColArr error: column array is empty");
		}
		return colArr;
	}

	public String getColVal(String _key){
		if(colArr.containsKey(_key)){
			return colArr.get(_key);
		}else{
			Log.logWrite("Model Error : No such a column name");
			return null;
		}
	}

	public boolean setCol(String _key, String _val){
		if(colArr.containsKey(_key)){
			colArr.put(_key,_val);
			return true;
		}else{
			Log.logWrite("Model Error : No such a column name");
			return false;
		}
	}

	public void setColArr(HashMap<String, String> _colArr){
		if(colArr.containsKey(pkName)){
			_colArr.put(pkName, getPKVal());
		}
		colArr = _colArr;
		return;
	}

	public void setPKVal(String _val){
		colArr.put(getPKName(), _val);
		return;
	}

	public boolean select(String _colNames, String _where){
		StringBuffer colNames = new StringBuffer();
		int          cnt      = 0;
		if(_where==null){
			if(getPKVal()!=""||getPKVal()!=null){
				_where = "WHERE "+pkName+ "=" + "'" +getPKVal()+ "'";
			}
		}
		if(_colNames==null){
			Iterator<String>  itr        = colArr.keySet().iterator();
			String            key        = new String();
			while(itr.hasNext()){
				key = itr.next();
				colNames.append(key);
				if(itr.hasNext()) colNames.append(",");
			}
		}else{
			cnt = 1;
		}
		if(cnt>0){
			String query = "SELECT " +colNames+ " FROM " +tableName+ " " +_where+ ";";
			if(read(query)){
				return true;
			}else{
				Log.logWrite("Model->select Error : No column name or where statement");
				return false;
			}
		}else{
			Log.logWrite("Model->select Error : No column name or where statement");
			return false;
		}
	}

	public boolean update(String _where){
		StringBuffer colNames = new StringBuffer();
		int          cnt      = 0;
		if(_where==null){
			if(getPKVal()!=""||getPKVal()!=null){
				_where = "WHERE "+pkName+ "=" + "'" +getPKVal()+ "'";
			}
		}
		Iterator<String>  itr = colArr.keySet().iterator();
		String            key = new String();
		String            val = new String();
		while(itr.hasNext()){
			key = itr.next();
			val = colArr.get(key);
			if(val.substring(0, 4)=="|FN|"){
				val.replace("|FN|", "");
				colNames.append(key);
				colNames.append("=");
				colNames.append(val);
			}else{
				colNames.append(key);
				colNames.append("=");
				colNames.append("'");
				colNames.append(val);
				colNames.append("'");
			}
			cnt++;
			if(itr.hasNext()) colNames.append(",");
		}
		if(cnt>0 && _where!=null){
			String query = "UPDATE " +tableName+ " SET " +colNames +_where+ ";";
			if(save(query)){
				return true;
			}else{
				Log.logWrite("Model->select Error : No column name or where statement");
				return false;
			}
		}
		return false;
	}
	
	public boolean insert(){
		StringBuffer      colNames = new StringBuffer();
		StringBuffer      vals     = new StringBuffer();
		int               cnt      = 0;
		Iterator<String>  itr      = colArr.keySet().iterator();
		String            key      = new String();
		String            val      = new String();
		while(itr.hasNext()){
			key = itr.next();
			val = colArr.get(key);
			colNames.append(key);
			if(val.substring(0, 4)=="|FN|"){
				val.replace("|FN|", "");
				vals.append(val);
			}else{
				vals.append("'");
				vals.append(val);
				vals.append("'");
			}
			cnt++;
			if(itr.hasNext()){
				colNames.append(",");
				vals    .append(",");
			}
		}
		if(cnt>0){
			String  query   = "INSERT INTO " +tableName+ "(" +colNames+ ")" + " VALUES " + "(" +vals+ ")" + ";";
			String  last_id = new String();
			if(save(query)){
				Database idb = new Database();
				if(idb.read("SELECT LAST_INSERT_ID() as id;")){
					last_id = idb.getRow().get("id");
					colArr.put(pkName, last_id);
					return true;
				}else{
					Log.logWrite("Model->insert Error : No column name or where statement");
					return false;
				}
			}else{
				Log.logWrite("Model->insert Error : No column name or where statement");
				return false;
			}
		}
		return false;	
	}
	
	public boolean delete(String _where){
		if(_where==null){
			if(pkName!=null || pkName!=""){
				_where = "WHERE " +pkName+ "=" + "'" +colArr.get(pkName)+ "'";
			}
		}
		if(_where!=null){
			String query = "DELETE FROM " +tableName +_where+ ";";  
			return save(query);
		}else{
			Log.logWrite("Model->delete Error : No column name or where statement");
			return false;
		}
	}
}
