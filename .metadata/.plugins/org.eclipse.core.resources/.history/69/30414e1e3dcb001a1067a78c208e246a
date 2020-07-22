package datnd;

import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.Statement;
import java.util.LinkedList;
import java.util.List;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

public class AppCrawl {

	public static void main(String[] args) {
//		readFile readFile = new readFile();
//		String appId = null;
//		int uploadDate;
//		int id = 0;
//		String fileUrl = "src/main/resources/html/" + id + ".html";
//		Document doc = null;
//		String html = null;
//		String url = null;
//		
//		FileWriter fileWriter;
//		
		Connection connection = connectJDBC.getSQLServerConnection();
		if(connection != null) {
			System.out.println("Connection success!");
		} else {
			System.out.println("Connection false");
		}
		try {
		Statement statement = connection.createStatement();
		String sqlSelect = "Select * from list_app limit  0,1000";
//		String sqlInsert = "Insert into newversion (appid, upload_date, version, upload_date_new, status, created_at)"
//				+ "value(?, ?, ?, ?, ?, ?)";
		
		ResultSet rs = statement.executeQuery(sqlSelect);
		
		List<AppInfo> listApp = new LinkedList<AppInfo>();
		
		while(rs.next()) {
			String appId = rs.getString("appid");
			int id = rs.getInt("id");
			int uploadDate = rs.getInt("uploadDate");
			String version = rs.getString("version");
			AppInfo app = new AppInfo(id, appId, version, uploadDate);
//			url = "https://play.google.com/store/apps/details?id="+ appId + "&hl=us";
//			System.out.println(id);
//			System.out.println(url);
			listApp.add(app);
			
//			try {
//				PreparedStatement st = connection.prepareStatement(sqlInsert);
//				st.setString(1, url);
//				st.setDate(2, new Date((long)uploadDate*1000));
//				st.setString(3, readFile.getVersion());
//				st.setDate(4, new Date((long)readFile.getDate()*1000));
//				st.setInt(5, 1);
//				st.setDate(6, new java.sql.Date(System.currentTimeMillis()));
//				st.executeUpdate();
//				st.close();
//			} catch(Exception e) {
//				e.printStackTrace();
//			}
		}
		statement.close();
		}catch(Exception e){
			System.out.print("Loi mySQL:");
			e.printStackTrace();
		}
		
		ExecutorService executor = Executors.newFixedThreadPool(50);
		for (AppInfo app : listApp) {
			titleCrawl thread = new titleCrawl(app.getId(), app.getAppId(), app.getUploadDate());
			executor.execute(thread);
		}
		executor.shutdown();
		while (executor.isTerminated()) {
			
		}
	}

}
