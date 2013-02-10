package org.gpstracker.mobile.res;



public class TimeRangeState {

	private int id;
	private String name;

	public TimeRangeState(int id, String name) {
		super();
		this.id = id;
		this.name = name;
	}

	public String getName() {
		return name;
	}

	public void setName(String name) {
		this.name = name;
	}

	public int getId() {
		return id;
	}

	public void setId(int id) {
		this.id = id;
	}

	@Override
	public String toString() {
		return getName();
	}

	public static TimeRangeState[] TimeRangeBuilder() {

		TimeRangeState[] rangeState = new TimeRangeState[] {
				new TimeRangeState(1, "Real Time Update"),
				new TimeRangeState(5000, "Every 5 sec"),
				new TimeRangeState(10000, "Every 10 sec"),
				new TimeRangeState(30000, "Every 30 sec"),
				new TimeRangeState(60000, "Every 1 Min"),
				new TimeRangeState(300000, "Every 5 min"),
				new TimeRangeState(600000, "Every 10 min"),
				new TimeRangeState(900000, "Every 15 min"),
				new TimeRangeState(1800000, "Every 30 min"),
				new TimeRangeState(3600000, "Every 1hr") };

		return rangeState;

	}
	
	public static int getRangeState(int id){
		TimeRangeState[] timeRanges = TimeRangeBuilder();
		int loop=0;
		for (TimeRangeState timeRangeState : timeRanges) {
			if(timeRangeState.getId()==id){
				return loop;
			}
			loop++;
		}
		
		return 0;
		
	}
}
