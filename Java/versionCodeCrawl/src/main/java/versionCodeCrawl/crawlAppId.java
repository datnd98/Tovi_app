package versionCodeCrawl;

import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;

public class crawlAppId implements Runnable{
	private Connection conn;
	private String sqlSelect = "Select appid from apkPureApp where status = 0";
	
	public crawlAppId (Connection conn) {
		this.conn = conn;
	}
	public void run() {
		try {
			Statement st = conn.createStatement();
			
			ResultSet rs = st.executeQuery(sqlSelect);
			
			while(rs.next()) {
				versionCodeCrawl.versionsCrawl(rs.getString("appid"), conn);
			}
			rs.close();
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}
