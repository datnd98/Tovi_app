package compareVersion;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.edge.EdgeDriver;

public class test {
	public static void main(String[] args) {
		System.setProperty("webdriver.edge.driver", "D:\\SeleniumWebDriver\\msedgedriver.exe");
		WebDriver driver = new EdgeDriver();
		String str = "apps";

		driver.get("https://apktrending.com/");
		driver.manage().window().maximize();

		for (int i = 0; i < 10; i++) {
			int index = (int) (Math.random() * 2);
			System.out.println(index);
			switch (index) {
			case 0:
				driver.findElement(By.xpath(".//a[@class='nav-link dropdown-toggle' and @href='/apps']")).click();
				str = "apps";
				break;
			case 1:
				driver.findElement(By.xpath(".//a[@class='nav-link dropdown-toggle' and @href='/apps']")).click();
				str = "games";
				break;
			}

			index = (int) (Math.random() * 12);
			System.out.println(index);
			switch (index) {
			case 0:
				driver.findElement(
						By.xpath("//a[@class='page-link' and @href='https://apktrending.com/" + str + "?page=2']"))
						.click();
				break;
			case 1:
				driver.findElement(
						By.xpath("//a[@class='page-link' and @href='https://apktrending.com/" + str + "?page=3']"))
						.click();
				break;
			case 2:
				driver.findElement(
						By.xpath("//a[@class='page-link' and @href='https://apktrending.com/" + str + "?page=4']"))
						.click();
				break;
			case 4:
				driver.findElement(
						By.xpath("//a[@class='page-link' and @href='https://apktrending.com/" + str + "?page=5']"))
						.click();
				break;
			case 5:
				driver.findElement(
						By.xpath("//a[@class='page-link' and @href='https://apktrending.com/" + str + "?page=6']"))
						.click();
				break;
			case 6:
				driver.findElement(
						By.xpath("//a[@class='page-link' and @href='https://apktrending.com/" + str + "?page=7']"))
						.click();
				break;
			case 7:
				driver.findElement(
						By.xpath("//a[@class='page-link' and @href='https://apktrending.com/" + str + "?page=8']"))
						.click();
				break;
			case 8:
				driver.findElement(
						By.xpath("//a[@class='page-link' and @href='https://apktrending.com/" + str + "?page=9']"))
						.click();
				break;
			case 9:
				driver.findElement(
						By.xpath("//a[@class='page-link' and @href='https://apktrending.com/" + str + "?page=10']"))
						.click();
				break;
			case 10:
				driver.findElement(
						By.xpath("//a[@class='page-link' and @href='https://apktrending.com/" + str + "?page=11']"))
						.click();
				break;
			case 11:
				driver.findElement(
						By.xpath("//a[@class='page-link' and @href='https://apktrending.com/" + str + "?page=21']"))
						.click();
				break;
			}
			
			index = (int) (Math.random()*12);
			System.out.println(index);
			
//			Element css = driver.findElement(By.xpath("//h2[@class='apk-app__title h6 mb-1']//a[@href='https://apktrending.com/']")).get;

		}
	}

}
