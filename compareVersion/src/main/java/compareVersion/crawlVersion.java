package compareVersion;

import java.sql.Connection;
import java.sql.PreparedStatement;

import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;

public class crawlVersion implements Runnable {
	private String appid;
	private String version0;
	private String url;
	private Connection conn;

	public crawlVersion(String appid, String version, Connection conn) {
		this.appid = appid;
		this.version0 = version;
		this.url = "https://apk.support/app-vi/" + appid;
		this.conn = conn;
	}

	public void run() {
		try {
			Document doc = Jsoup.connect(url).get();
			String version1 = doc.select("span[class=vers]").text();
			if (version0.equals(version1) != true) {
				String sqlInsert = "Insert into compareVersion (appid, version0, version1)" + "value(?, ?, ?)";

				PreparedStatement st = conn.prepareStatement(sqlInsert);
				st.setString(1, appid);
				st.setString(2, version0);
				st.setString(3, version1);
				st.executeUpdate();
				st.close();
			}

		} catch (Exception e) {
			e.printStackTrace();
		}
	}
}
