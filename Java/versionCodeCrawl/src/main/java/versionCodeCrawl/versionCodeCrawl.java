package versionCodeCrawl;

import java.sql.Connection;
import java.sql.Date;
import java.sql.PreparedStatement;
import java.sql.Statement;
import java.text.SimpleDateFormat;
import java.util.LinkedList;
import java.util.List;

import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.nodes.Element;
import org.jsoup.select.Elements;

public class versionCodeCrawl {
	public static void versionsCrawl(String appid, Connection conn) {
		String url = "https://apkpure.com" + appid + "/versions";
		String url1 = "https://apkpure.com" + appid;
		List<String> uploadDate = new LinkedList<String>();
		List<String> versionCode = new LinkedList<String>();
		List<String> versionString = new LinkedList<String>();
		String sqlInsert1 = "Insert ignore into apkPureApp (appid, status) value(?, ?)";
		String sqlInsert2 = "Insert ignore into versioncode (appid, versionString, versionCode, uploadDate) value(?, ?, ?, ?)";
		String sqlUpdate = "Update apkPureApp set status = \"1\" where appid ='" + appid + "'";

		try {
			Document doc = Jsoup.connect(url1).get();
			Elements links = doc.select("a[href]");
			for (Element link : links) {
				String a = link.attr("href");
				if (a.contains("/com.") == true && a.contains("http") == false && a.contains("download") == false
						&& a.contains(appid) == false) {
					PreparedStatement st1 = conn.prepareStatement(sqlInsert1);
					st1.setString(1, a);
					st1.setInt(2, 0);
					st1.executeUpdate();
					st1.close();
				}
			}
			
			doc = Jsoup.connect(url).get();
			Elements ps = doc.select("p");
			for (Element p : ps) {
				String a = p.text();
				if (a.contains("Update on: ") == true) {
					int start = a.indexOf(":", 1);
					String date = a.substring(start + 2, a.length());
					uploadDate.add(date);
				}
			}
			List<String> versionStr = doc.select("div[class=ver-info-top]").eachText();
			for (String version : versionStr) {
				int start = version.indexOf("(", 3);
				int end = version.indexOf(")", start + 1);
				versionCode.add(version.substring(start + 1, end));
				end = start - 1;
				start = version.indexOf(".", 3) - 1;
				versionString.add(version.substring(start, end));
			}
			
			for (int i = 0; i < versionString.size(); i++) {
				PreparedStatement st1 = conn.prepareStatement(sqlInsert2);
				st1.setString(1, appid);
				st1.setString(2, versionString.get(i));
				st1.setString(3, versionCode.get(i));
				st1.setString(4, uploadDate.get(i));
				st1.executeUpdate();
				st1.close();
			}
			
			Statement st = conn.createStatement();
			st.execute(sqlUpdate);
			st.close();

		} catch (Exception e1) {
			// TODO Auto-generated catch block
			e1.printStackTrace();
			try {
				Statement st2 = conn.createStatement();
				st2.execute(sqlUpdate);
				st2.close();
			} catch (Exception e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
			
		}
	}
}
