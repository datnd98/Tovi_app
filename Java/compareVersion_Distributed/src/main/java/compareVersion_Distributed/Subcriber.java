package compareVersion_Distributed;

import org.eclipse.paho.client.mqttv3.MqttClient;
import org.eclipse.paho.client.mqttv3.MqttConnectOptions;
import org.eclipse.paho.client.mqttv3.MqttException;

public class Subcriber {
	private static final String userName = "datnd";
	private static final String passwd = "tovi123@";
	
	public static void main(String[] args) throws MqttException {

		System.out.println("== START SUBSCRIBER ==");

		MqttClient client = new MqttClient("tcp://localhost:1883", MqttClient.generateClientId());
//		client.setCallback();
		MqttConnectOptions opts = new MqttConnectOptions();
		opts.setUserName(userName);
		opts.setPassword(passwd.toCharArray());
		client.setCallback(new MessageProcess());
		client.connect(opts);

		client.subscribe("tovi/versionCompare");
	}

}
