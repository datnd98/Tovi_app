package datnd;

public class AppInfo {
	private String appId;
	private int uploadDate;
	
	public AppInfo(String appId,int uploadDate) {
		this.appId = appId;
		this.uploadDate = uploadDate;
	}

	public String getAppId() {
		return appId;
	}

	public void setAppId(String appId) {
		this.appId = appId;
	}

	public int getUploadDate() {
		return uploadDate;
	}

	public void setUploadDate(int uploadDate) {
		this.uploadDate = uploadDate;
	}
}
