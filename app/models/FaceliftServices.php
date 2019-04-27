<?php


Class FaceliftServices extends Eloquent {
	protected $table = 'faceliftservices';
	protected $primaryKey = 'client_id';

	public function customer() {
		return $this->belongsTo('FaceliftCustomers','client_id');

	}

	public function attendant() {
		return $this->hasOne('FaceliftStaff','staff_id');
	}

	public function staff() {
		return $this->belongsTo('FaceliftStaff','staff_id');
	}

	Public function addServices($array) {
		$model = new FaceliftServices;

		$staff = FaceliftStaff::where('name','=',strtolower($array['staff attendant']))->get();
		$model->service_type = isset($array['service']) ? $array['service'] : '';
		$model->service_start = isset($array['service start']) ? strftime("%H:%M:%S",strtotime($array['service start'])) : '00:00:00';
		$model->service_end = isset($array['service end']) ? strftime("%H:%M:%S",strtotime($array['service end'])) : '00:00:00';
		$model->attendant = !empty($staff) && isset($staff[0]) ? $staff[0]->name : 'Staff Unknown';
		$model->amount = isset($array['fee paid']) ? str_replace(',','',$array['fee paid']) : 0;
		$model->arrival = isset($array['arrival']) ? strftime("%H:%M:%S",strtotime($array['arrival'])) : '00:00:00';
		$model->departure = isset($array['departure']) ? strftime("%H:%M:%S",strtotime($array['departure'])) : '00:00:00';
		$model->rebooked_date = isset($array['rebooked date']) ? strftime("%Y-%m-%d",strtotime($array['rebooked date'])) : '0000-00-00';
		$model->client_id = isset($array['client_id']) ? $array['client_id'] : '';
		$model->staff_id = !empty($staff) && isset($staff[0]) ? $staff[0]->staff_id : '';
		$model->month = isset($array['date']) ? strftime("%m", strtotime($array['date'])) : '00';
		$model->year = isset($array['date']) ? strftime("%Y", strtotime($array['date'])) : '0000';
		$model->created_at = isset($array['date']) ?  strftime("%Y-%m-%d %H:%M:%S", strtotime($array['date'])) : '0000-00-00 00:00:00';

		if(!empty($staff) && !empty($array['client_id']) && $model->save()) {
			return true;
		} else {
			return False;
		}
	}
}