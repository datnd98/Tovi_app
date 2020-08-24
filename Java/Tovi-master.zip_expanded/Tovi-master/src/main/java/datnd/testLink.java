package datnd;

import java.io.IOException;

import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;

public class testLink {

	public static void main(String[] args) {
		try {
			for (int i = 0; i < 10; i++) {
			Document doc = Jsoup.connect("https://apkpure.com/").get();
			String html = doc.html();
			System.out.println(html);
			}
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

	}

}
