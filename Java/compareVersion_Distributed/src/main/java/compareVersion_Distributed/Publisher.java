package compareVersion_Distributed;

import java.util.Scanner;

import org.eclipse.paho.client.mqttv3.MqttClient;
import org.eclipse.paho.client.mqttv3.MqttConnectOptions;
import org.eclipse.paho.client.mqttv3.MqttException;
import org.eclipse.paho.client.mqttv3.MqttMessage;

public class Publisher {
	private static final String userName = "datnd";
	private static final String passwd = "tovi123@";

	public static void main(String[] args) throws MqttException {
		String messageString = "Hello World from Java!";
		Subcriber.main(args);
		while (true) {
			Scanner sc = new Scanner(System.in);
			System.out.print("Message: ");
			messageString = sc.next();
			sc.close();

			System.out.println("== START PUBLISHER ==");

			MqttClient client = new MqttClient("tcp://localhost:1883", MqttClient.generateClientId());
			MqttConnectOptions opts = new MqttConnectOptions();
			opts.setUserName(userName);
			opts.setPassword(passwd.toCharArray());
			client.connect(opts);
			MqttMessage message = new MqttMessage();
			message.setPayload(messageString.getBytes());
			client.publish("tovi/versionCompare", message);

			System.out.println("\tMessage '" + messageString + "' to 'tovi/versionCompare'");

			client.disconnect();

			System.out.println("== END PUBLISHER ==");
		}
	}
}
