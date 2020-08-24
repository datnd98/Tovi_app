package compareVersion;

import java.sql.Connection;
import java.sql.ResultSet;
import java.sql.Statement;
import java.util.LinkedList;
import java.util.List;
import java.util.concurrent.ExecutorService;
import java.util.concurrent.Executors;

public class crawlApp {

	public static void main(String[] args) {
		System.setProperty("proxySet", "true");
		System.setProperty("socksProxyHost", "173.44.37.82");
		System.setProperty("socksProxyPort", "1080");

		List<NewVersion> listApp = new LinkedList<NewVersion>();
		Connection conn = connectJDBC.getSQLServerConnection();
		if(conn != null) {
			System.out.println("Connect success!");
		}else {
			System.out.println("Connect failed!");
		}
		
		try {
			Statement statement = conn.createStatement();
			String sqlSelect = "Select appid, version from newversion where version != \"\" limit 0,100";
			
			ResultSet rs = statement.executeQuery(sqlSelect);
			
			while(rs.next()) {
				String appid = rs.getString("appid");
				String version = rs.getString("version");
				System.out.println(appid);
				
				NewVersion newApp = new NewVersion(appid, version);
				listApp.add(newApp);
			}
			rs.close();
			statement.close();
			
			ExecutorService executor = Executors.newFixedThreadPool(1);
			for (NewVersion app : listApp) {
				crawlVersion thread = new crawlVersion(app.getAppid(), app.getVersion(), conn);
				executor.execute(thread);
			}
			executor.shutdown();
		}catch(Exception e) {
			e.printStackTrace();
		}
	}

}
