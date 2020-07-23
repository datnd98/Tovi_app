package datnd;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.Statement;
import java.util.LinkedList;
import java.util.List;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

//import org.apache.commons.lang3.time.StopWatch;

public class AppCrawl {

	public static void main(String[] args) {
//		StopWatch watchRun = new StopWatch();
//		StopWatch connectDBRun = new StopWatch();
//		StopWatch crawlRun = new StopWatch();
//		StopWatch selectRun = new StopWatch();
//		watchRun.start();
		FileWriter fw = null;
		File logFile = new File("/home/TOVI_App/CrawlNewVersion/logs/log.txt");
//		/home/TOVI_App/CrawlNewVersion/logs/
		List<AppInfo> listApp = new LinkedList<AppInfo>();
		try {
			fw = new FileWriter(logFile);
//			connectDBRun.start();
			Connection connection = connectJDBC.getSQLServerConnection();
			if (connection != null) {
				System.out.println("Connection success!");
//				connectDBRun.stop();
//				System.out.println("Thoi gian ket noi DB ---> " + connectDBRun.getTime());
			} else {
				System.out.println("Connection false");
			}
			Statement statement = connection.createStatement();
			String sqlSelect = "Select appid, uploadDate from list_app limit 0,100000";

//			selectRun.start();
			ResultSet rs = statement.executeQuery(sqlSelect);
//			selectRun.stop();
//			System.out.println("Thoi gian Select ---> " + selectRun.getTime());

			ExecutorService executor = Executors.newFixedThreadPool(50);

			while (rs.next()) {
				String appId = rs.getString("appid");
				int uploadDate = rs.getInt("uploadDate");
				AppInfo app = new AppInfo(appId, uploadDate);
				listApp.add(app);
			}
			statement.close();
			
//			crawlRun.start();
			for (AppInfo app : listApp) {
				titleCrawl thread = new titleCrawl(app.getAppId(), app.getUploadDate(), connection, logFile);
				executor.execute(thread);
			}
			listApp.clear();
			executor.shutdown();
			if (executor.isTerminated() == true) {
//				crawlRun.stop();
//				FileWriter timeFw = new FileWriter(new File("/home/TOVI_App/CrawlNewVersion/time.txt"));
//				timeFw.write(String.valueOf(crawlRun.getTime()));
//				timeFw.close();
//				System.out.println("Thoi gian crawl ---> " + crawlRun.getTime());
			}
		} catch (Exception e) {
				try {
					fw.write("Loi ---> " + e.getMessage() + "\n");
					fw.close();
				} catch (IOException e1) {
					// TODO Auto-generated catch block
					e1.printStackTrace();
				}
		}
	}
}
