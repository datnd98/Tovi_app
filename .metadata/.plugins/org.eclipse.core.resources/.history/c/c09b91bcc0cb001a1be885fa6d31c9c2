package datnd;

import java.io.File;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;

import com.google.gson.JsonArray;
import com.google.gson.JsonElement;
import com.google.gson.JsonParser;

public class readFile {
	int date1;
	String version;
	public void readFile1(String url) {
	File htmlFile = new File(url);
	try {
		Document doc = Jsoup.parse(htmlFile, "utf-8");
		String html = doc.toString();
		String regex1 = ">AF_initDataCallback\\(\\{key: 'ds:5'(.*?)\\}\\);<\\/script>";
		String regex2 = ">AF_initDataCallback\\(\\{key: 'ds:8'(.*?)\\}\\);<\\/script>";
		Pattern pattern1 = Pattern.compile(regex1, Pattern.DOTALL);
		Pattern pattern2 = Pattern.compile(regex2, Pattern.DOTALL);
		Matcher matcher1 = pattern1.matcher(html);
		Matcher matcher2 = pattern2.matcher(html);
		if(matcher1.find()) {
			if(matcher1.groupCount() >= 1) {
				String res1 = matcher1.group(1);
				String test1 = res1.substring(res1.indexOf("data:"));
				test1 = test1.substring(5);
				JsonParser parser1 = new JsonParser();
				JsonElement tradeElement1 = parser1.parse(test1);
				JsonArray trade1 = tradeElement1.getAsJsonArray();
				String date = trade1.get(0).getAsJsonArray().get(12).getAsJsonArray().get(8).getAsJsonArray().get(0).getAsString();
				date1 = Integer.parseInt(date);
				}
			}
		if(matcher2.find()) {
			if(matcher2.groupCount() >= 1) {
				String res2 = matcher2.group(1);
				String test2 = res2.substring(res2.indexOf("data:"));
				test2 = test2.substring(5);
				JsonParser parser2 = new JsonParser();
				JsonElement tradeElement2 = parser2.parse(test2);
				JsonArray trade2 = tradeElement2.getAsJsonArray();
				version = trade2.get(1).getAsString();
				}
			}
		} catch (Exception e) {
		e.printStackTrace();
		}
	htmlFile.delete();
	}
	
	public int getDate() {
		return date1;
	}
	
	public String getVersion() {
		return version;
	}
}
