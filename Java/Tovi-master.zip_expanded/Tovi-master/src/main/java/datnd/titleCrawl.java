package datnd;

import java.io.File;
import java.io.FileWriter;
import java.sql.*;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;
import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;

public class titleCrawl implements Runnable {
	private File logFile;
	private File htmlFile;
	private File dataFile;
	private readFile readFile = new readFile();
	private String appId;
	private int uploadDate;
	private String version;
	private Document doc = null;
	private String html = null;
	private String url;
	private FileWriter fileWriter;
	private FileWriter fw;

	public titleCrawl(String appId, int uploadDate, File file, File dataFile) {
		super();
		this.appId = appId;
		this.uploadDate = uploadDate;
		this.logFile = file;
		this.dataFile = dataFile;
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
				if (uploadDate < readFile.getDate()) {
					version = readFile.getVersion();
					try {
						String dataString = appId + "\t" + String.valueOf(new Date((long) uploadDate * 1000)) + "\t" + version + "\t" + String.valueOf(new Date((long) readFile.getDate() * 1000))
						+ "\t" + 1 + "\t" + datenow + "\n";
						fileWriter = new FileWriter(dataFile, true);
						fileWriter.write(dataString);
						fileWriter.close();
					} catch (Exception e) {
						fw.write("Loi ghi file ---> " + e.getMessage() + "\n");
					}
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