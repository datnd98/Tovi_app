package versionCodeCrawl;

import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.Statement;
import java.util.LinkedList;
import java.util.List;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

public class Main {

	public static void main(String[] args) {
		List<String> listApp = new LinkedList<String>();
		Connection conn = connectJDBC.getSQLServerConnection();
		ExecutorService executor = Executors.newFixedThreadPool(100);
		try {
			Statement st = conn.createStatement();
			listApp.add("start");
			while(listApp.isEmpty() == false) {
				listApp.clear();
				crawlAppId thread = new crawlAppId(conn);
				executor.execute(thread);
				ResultSet rs = st.executeQuery("Select appid from apkPureApp where status = 0");
				while (rs.next()) {
					listApp.add(rs.getString("appid"));
				}
				rs.close();
			}
			executor.shutdown();
			st.close();
		} catch (Exception e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}		

	}

}
