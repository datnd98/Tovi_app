package datnd;

import java.sql.*;

public class connectJDBC {
	public static Connection getSQLServerConnection() {
	final String url = "jdbc:mysql://45.76.152.39/crawlTest?useUnicode=true&characterEncoding=utf-8";
	final String user = "root";
	final String password = "tovi";
	
	
		try {
			return DriverManager.getConnection(url,user,password);
		} catch (Exception e){
			e.printStackTrace();
		}
		return null;
	}
}
