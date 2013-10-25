package smvc;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;

public class Config {
	//Document root path
	public static String _DOC_ROOT_;
	
	//Service infomation
	public static String __SERVICE_NAME__;
	public static String __SERVICE_HOST__;
	public static String __BASE_DIR__    ;
	
	//Database information
	public static String __DB_HOST__;
	public static String __DB_NAME__;
	public static String __DB_CHAR__;
	public static String __DB_USER__;
	public static String __DB_PASS__;
	
	//Log information
	public static boolean __LOG_MODE__; // true|false
	public static String  __LOG_PATH__;
	
	public Config(){
		_DOC_ROOT_       = "";
		
		__SERVICE_NAME__ = "";
		__SERVICE_HOST__ = "";
		__BASE_DIR__     = "";
		
		__DB_HOST__      = "";
		__DB_NAME__      = "";
		__DB_USER__      = "";
		__DB_PASS__      = "";
		__DB_CHAR__      = "";
		
		Date             date     = new Date();
		SimpleDateFormat format   = new SimpleDateFormat("yyyyMMdd", Locale.KOREA);		
		String           yyyymmdd = format.format(date);
		__LOG_MODE__     = true;
		__LOG_PATH__     = _DOC_ROOT_ + "/log/log_" + yyyymmdd + ".txt";
	}
}
