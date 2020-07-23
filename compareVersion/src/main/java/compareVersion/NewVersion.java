package compareVersion;

public class NewVersion {
	private String appid;
	private String version;
	
	public NewVersion(String appid, String version) {
		this.appid = appid;
		this.version = version;
	}

	public String getAppid() {
		return appid;
	}

	public void setAppid(String appid) {
		this.appid = appid;
	}

	public String getVersion() {
		return version;
	}

	public void setVersion(String version) {
		this.version = version;
	}

}
