package datnd;

public class AppInfo {
	private int id;
	private String appId;
	private String version;
	private int uploadDate;
	
	public AppInfo(int id, String appId, String version, int uploadDate) {
		this.id = id;
		this.appId = appId;
		this.version = version;
		this.uploadDate = uploadDate;
	}

	public String getAppId() {
		return appId;
	}

	public void setAppId(String appId) {
		this.appId = appId;
	}

	public String getVersion() {
		return version;
	}

	public void setVersion(String version) {
		this.version = version;
	}

	public int getUploadDate() {
		return uploadDate;
	}

	public void setUploadDate(int uploadDate) {
		this.uploadDate = uploadDate;
	}
	
	public int getId() {
		return id;
	}

	public void setId(int id) {
		this.id = id;
	}

}
