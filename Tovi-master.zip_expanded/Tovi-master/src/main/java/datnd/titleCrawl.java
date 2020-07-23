package datnd;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.sql.*;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;

public class titleCrawl implements Runnable {
//	private final Logger logger = LogManager.getLogger(titleCrawl.class);
	private File logFile;
	private File htmlFile;
	private Connection con;
	private readFile readFile = new readFile();
	private String appId;
	private int uploadDate;
	private String version;
	private Document doc = null;
	private String html = null;
	private String url;
	private FileWriter fileWriter;
	private FileWriter fw;

	public titleCrawl(String appId, int uploadDate, Connection con, File file) {
		super();
		this.appId = appId;
		this.uploadDate = uploadDate;
		this.con = con;
		this.logFile = file;
		this.url = "https://play.google.com/store/apps/details?id=" + this.appId + "&hl=us";
		this.htmlFile = new File("/home/TOVI_App/CrawlNewVersion/html/" + this.appId + ".html");
	}

	public void run() {
		DateTimeFormatter dtf = DateTimeFormatter.ofPattern("yyyy/MM/dd HH:mm:ss");
		LocalDateTime now = LocalDateTime.now();
		String datenow = dtf.format(now);
		try {
			fw = new FileWriter(logFile, true);
			try {
				doc = Jsoup.connect(url).get();
				html = doc.html();
				try {
					fileWriter = new FileWriter(htmlFile);
					fileWriter.write(html);
					fileWriter.close();
				} catch (Exception e) {
					fw.write("Loi ghi file ---> " + e.getMessage() + "\n");
				}
				readFile.readFile1(htmlFile, fw);
				if(htmlFile.exists() == true) {
					System.out.println("xoa file html --->");
					htmlFile.delete();
				}
				if (uploadDate < readFile.getDate()) {
					version = readFile.getVersion();
				}
				try {
					String sqlInsert = "Insert into newversion (appid, upload_date, version, upload_date_new, status, created_at)"
							+ "value(?, ?, ?, ?, ?, ?)";
					PreparedStatement st = con.prepareStatement(sqlInsert);
					st.setString(1, appId);
					st.setDate(2, new Date((long) uploadDate * 1000));
					st.setString(3, version);
					st.setDate(4, new Date((long) readFile.getDate() * 1000));
					st.setInt(5, 1);
					st.setString(6, datenow);
					st.executeUpdate();
					st.close();
				} catch (Exception e) {
					fw.write("Loi insert DB ---> " + e.getMessage() + "\n");
				}
			} catch (Exception e) {
				fw.write("Loi app da bi xoa ---> " + e.getMessage() + "\n");
			}
			fw.close();
		} catch (Exception e) {
			e.printStackTrace();
		}
	}
}