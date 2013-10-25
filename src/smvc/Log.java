package smvc;

import java.io.FileWriter;
import java.io.BufferedWriter;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.Locale;

public class Log{
	private static Date             date    ;
	private static SimpleDateFormat dateFM  ;
	private static String           dateStr ;
	
	public Log(){
		date     = new Date();
		dateFM   = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss", Locale.KOREA);
		dateStr  = dateFM.format(date);
	}
	
	public static void logWrite( String _msg ){
		if(Config.__LOG_MODE__){
			String msg = "["+dateStr+"]"+ _msg;
			System.out.println(msg);
			try{
				BufferedWriter fw = new BufferedWriter(new FileWriter(Config.__LOG_PATH__,true));
				fw.write(msg);
				fw.newLine();
				fw.flush();
				fw.close();
			}catch(Exception ex){
				System.out.println("LogWrite error : " + ex.getMessage());
			}
		}
	}
}
