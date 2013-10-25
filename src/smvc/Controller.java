package smvc;

import java.io.IOException;
import java.util.Map;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServlet;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

public class Controller extends HttpServlet{
	private static final long serialVersionUID = 1L;
	private Map<String, String[]> req      ;
	private String                reqMethod;
	private Map<String, String[]> sess     ;
	private Map<String, String[]> cook     ;
	
	public void doGet(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException
	{
		doPost(request, response);
	}
	
	public void doPost(HttpServletRequest request, HttpServletResponse response) 
			throws ServletException, IOException
	{
		init();
	}
	
	@SuppressWarnings("unchecked")
	public void init(HttpServletRequest request){
		req       = request.getParameterMap();
		reqMethod = request.getMethod();
		sess      = (Map<String, String[]>) request.getSession();
		//cook      = (Map<String, String[]>) request.getCookies();
	}

}
