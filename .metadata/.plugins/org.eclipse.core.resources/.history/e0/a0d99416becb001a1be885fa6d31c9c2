package datnd;

import java.io.FileWriter;
import java.io.IOException;
import java.sql.*;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.LinkedList;
import java.util.List;
import java.util.concurrent.Executor;

import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;

import datnd.connectJDBC;

public class titleCrawl implements Runnable {
	private readFile readFile = new readFile();
	private String appId;
	private int uploadDate;
	private int id;
	private String fileUrl;
	private Document doc = null;
	private String html = null;
	private String url;
	private FileWriter fileWriter;
	
	public titleCrawl (int id, String appId, int uploadDate) {
		super();
		this.id = id;
		this.appId = appId;
		this.uploadDate = uploadDate;
		this.fileUrl = "/var/www/html/" + this.id + ".html";
		this.url = "https://play.google.com/store/apps/details?id="+ this.appId + "&hl=us";
	}
	
	public void run() {
		Connection connection = connectJDBC.getSQLServerConnection();
		if(connection != null) {
			System.out.println("Connection success!");
		} else {
			System.out.println("Connection false");
		}
//		try {
//		Statement statement = connection.createStatement();
//		String sqlSelect = "Select * from list_app limit" + startPoint + ",50";
//		String sqlInsert = "Insert into newversion (appid, upload_date, version, upload_date_new, status, created_at)"
//				+ "value(?, ?, ?, ?, ?, ?)";
//		
//		ResultSet rs = statement.executeQuery(sqlSelect);
//		
//		statement.close();
//		
//		while(rs.next()) {
//			appId = rs.getString("appid");
//			id = rs.getInt("id");
//			uploadDate = rs.getInt("uploadDate");
//			url = "https://play.google.com/store/apps/details?id="+ appId + "&hl=us";
//			System.out.println(id);
//			System.out.println(url);
			try {
			doc = Jsoup.connect(url).get();
			}catch(Exception e) {
				System.out.print("Loi jsoup: ---->");
				e.printStackTrace();
			}
			html = doc.html();
			try {
				fileWriter = new FileWriter(fileUrl);
				fileWriter.write(html);
				fileWriter.close();
			} catch(Exception e){
				System.out.print("Loi FileWriter: ---->");
				e.printStackTrace();
			}
			readFile.readFile1(fileUrl);
			if(uploadDate < readFile.getDate()) {
				System.out.println("co ban moi: " + readFile.getVersion());
				Date d = new Date((long)readFile.getDate()*1000);
				DateFormat f = new SimpleDateFormat("yyyy-MM-dd' 'HH:mm:ss.mmm' '");
		        System.out.println("Ngay cap nhat: " + f.format(d) + "\n");
			}
			try {
				String sqlInsert = "Insert into newversion (appid, upload_date, version, upload_date_new, status, created_at)"
						+ "value(?, ?, ?, ?, ?, ?)";
				PreparedStatement st = connection.prepareStatement(sqlInsert);
				st.setString(1, url);
				st.setDate(2, new Date((long)uploadDate*1000));
				st.setString(3, readFile.getVersion());
				st.setDate(4, new Date((long)readFile.getDate()*1000));
				st.setInt(5, 1);
				st.setDate(6, new java.sql.Date(System.currentTimeMillis()));
				st.executeUpdate();
				st.close();
			} catch(Exception e) {
				System.out.println("Loi database: ---->");
				e.printStackTrace();
			}
//		}
//		}catch(Exception e){
//			System.out.print("Loi mySQL:");
//			e.printStackTrace();
//		}
	}
			
//	public static void main(String[] args) {
////		readFile readFile = new readFile();
////		String appId = null;
////		int uploadDate;
////		int id = 0;
////		String fileUrl = "src/main/resources/html/" + id + ".html";
////		Document doc = null;
////		String html = null;
////		String url = null;
////		
////		FileWriter fileWriter;
//		List<AppInfo> listApp = new LinkedList<AppInfo>();
////		
//		Connection connection = connectJDBC.getSQLServerConnection();
//		if(connection != null) {
//			System.out.println("Connection success!");
//		} else {
//			System.out.println("Connection false");
//		}
//		try {
//		Statement statement = connection.createStatement();
//		String sqlSelect = "Select * from list_app limit  0,1000";
//		String sqlInsert = "Insert into newversion (appid, upload_date, version, upload_date_new, status, created_at)"
//				+ "value(?, ?, ?, ?, ?, ?)";
//		
//		ResultSet rs = statement.executeQuery(sqlSelect);
//		
//		statement.close();
//		
//		while(rs.next()) {
//			String appId = rs.getString("appid");
//			int id = rs.getInt("id");
//			int uploadDate = rs.getInt("uploadDate");
//			String version = rs.getString("version");
//			AppInfo app = new AppInfo(id, appId, version, uploadDate);
////			url = "https://play.google.com/store/apps/details?id="+ appId + "&hl=us";
////			System.out.println(id);
////			System.out.println(url);
//			listApp.add(app);
//			
////			try {
////				PreparedStatement st = connection.prepareStatement(sqlInsert);
////				st.setString(1, url);
////				st.setDate(2, new Date((long)uploadDate*1000));
////				st.setString(3, readFile.getVersion());
////				st.setDate(4, new Date((long)readFile.getDate()*1000));
////				st.setInt(5, 1);
////				st.setDate(6, new java.sql.Date(System.currentTimeMillis()));
////				st.executeUpdate();
////				st.close();
////			} catch(Exception e) {
////				e.printStackTrace();
////			}
//		}
//		}catch(Exception e){
//			System.out.print("Loi mySQL:");
//			e.printStackTrace();
//		}
//		
//		
//	}
}
