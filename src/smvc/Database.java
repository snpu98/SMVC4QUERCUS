package smvc;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.ResultSetMetaData;
import java.sql.SQLException;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;

public class Database{
	private Connection							conn;
	private ResultSet							rs  ;
	private ResultSetMetaData 					rsmd;
	private Statement							stmt;
	private HashMap<String, String>				row ;
	private ArrayList<HashMap<String, String>>	rows;
	
	public Database(){
		row  = new HashMap<String, String>();
		rows = new ArrayList<HashMap<String, String>>();
		
		try{
			Class.forName("com.mysql.jdbc.Driver").newInstance();
		}catch(Exception ex){
			Log.logWrite("Database->__construct Error : " + ex.getMessage());
		}

		try{
			conn = DriverManager.getConnection("jdbc:mysql://"+Config.__DB_HOST__+":"+Config.__DB_NAME__, Config.__DB_USER__, Config.__DB_PASS__);
			this.save("SET NAMES " + Config.__DB_CHAR__);
		}catch(SQLException ex){
			Log.logWrite("Database__construct Error : " + ex.getMessage());
		}
	}

	public void finalize() throws SQLException{
		if(  rs != null) {  rs.close();  rs = null;}
		if(stmt != null) {stmt.close();stmt = null;}
		if(conn != null) {conn.close();conn = null;}
	}
	
	public void clear(){
		row  = new HashMap<String, String>();
		rows = new ArrayList<HashMap<String, String>>();
	}

	public boolean read(String _query){
		clear();

		try{
			stmt = conn.createStatement();
			rs   = stmt.executeQuery(_query);
			rsmd = rs  .getMetaData();
			try{
				while(rs.next()){
					int    cnt = rsmd.getColumnCount();
					String key = null;
					String val = null;
					for(int i=0; i<cnt; i++){
						key = rsmd.getColumnName(i);
						val = rs.getString(i);
						row.put(key, val);
					}
					rows.add(row);
				}
				Log.logWrite("Database->read Query : " +_query);
				return true;
			}catch(Exception ex){
				Log.logWrite("Database->read Error : " +ex.getMessage()+ "/" +_query);
				return false;
			}
		} catch(Exception ex){
			Log.logWrite("Database->read Error : " +ex.getMessage());
			return false;
		}
	}

	public boolean save(String _query){
		clear();

		try {
			stmt = conn.createStatement();
			rs   = stmt.executeQuery(_query);
			Log.logWrite("Database->save Query : " +_query);
			return true;
		} catch(Exception ex){
			Log.logWrite("Database->save Error : " + ex.getMessage()+ "/" +_query);
			return false;
		}
	}
	
	public HashMap<String, String> getRow(){
		return row;
	}
	
	public ArrayList<HashMap<String, String>> getRows(){
		return rows;
	}
	
	public String getJSON(){
		
		int		cnt = rows.size();
		String	tmp = new String();
		String	key = new String();
		String	val = new String();
		String	str = new String();
		
		for(int i=0; i<cnt; i++){
			Iterator<String> itr = row.keySet().iterator();
			while(itr.hasNext()){
				key = itr.next();
				val = rows.get(i).get(key);
				str = "'" + key + "':";
				tmp = val;
				if( tmp != null ){
					tmp = tmp.replace("'" , "`" );
					tmp = tmp.replace("\"", "``");
				}
				str += "'" + tmp + "',";
			}
			str += ",";
			str  = str.replaceAll(",,", "");
			str += "},";
		}
		str += ",";
		str  = str.replaceAll(",,", "");
		
		return str;
	}
	
	public String getXML(){
		int          cnt = rows.size();
		String       key = new String();
		String       val = new String();
		StringBuffer str = new StringBuffer();
		
		str.append("<rows>");
		for(int i=0; i<cnt; i++){
			str.append("<row>");
			Iterator<String> itr = row.keySet().iterator();
			while(itr.hasNext()){
				key = itr.next();
				val = rows.get(i).get(key);
				str.append("<"+key+">" + val + "</"+key+">");
			}
			str.append("</row>");
		}
		str.append("</rows>");
		
		return str.toString();
	}
}