<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('UserTableSeeder');
		$this->call('CustomerTableSeeder');
		$this->call('StaffTableSeeder');
		$this->call('CommissionTableSeeder');
		$this->call('ServicesTableSeeder');
		$this->call('ReferralsTableSeeder');
		$this->call('LpTableSeeder');
		//$this->call('BCTableSeeder');

		$this->command->info('User Table Seeded');
	}



}

Class UserTableSeeder extends Seeder {


	public function run() {
		DB::table('faceliftusers')->delete();


		FaceliftUsers::create(['username'=>'facelift','password'=>Hash::make('facelift'),'super_admin'=>'1']);
	}
}

Class CustomerTableSeeder extends Seeder {
	public function run() {
		DB::table('faceliftcustomers')->delete();


		FaceliftCustomers::create(
			[
			'client_id'=>1234,
			'client_type'=>'referral',
			'client_name'=>'demo',
			'gender'=>'male',
			'houseNo'=>'138',
			'street'=>'Demo street',
			'location'=>'Lagos',
			'city'=>'Ibeshe',
			'religion'=>'Christianity',
			'status'=>'single',
			'wedding_anniversary'=>strftime('%Y-%m-%d',strtotime('today')),
			'DOB'=>strftime('%Y-%m-%d',strtotime('today')),
			'occupation'=>'stylist',
			'phone_no'=>'08123456789',
			'email'=>'john@doe.com',
			'referee'=>'demo',
			'flk'=>'',
			'month'=>strftime('%m',strtotime('today')),
			'year'=>strftime('%Y',strtotime('today')),
			'created_at'=>strftime('%Y-%m-%d %H:%M:%S',strtotime('today')),
			'updated_at'=>strftime('%Y-%m-%d %H:%M:%S',strtotime('today')),
			]);
	}
}

Class StaffTableSeeder extends Seeder {
	public function run() {
		DB::table('faceliftstaff')->delete();


		FaceliftStaff::create(
			[
			'staff_id'=>4321,
			'name'=>'demo',
			'address'=>'Ibeshe',
			'phone_no'=>'08123456789',
			'email'=>'john@doe.com',
			'month'=>strftime('%m',strtotime('today')),
			'year'=>strftime('%Y',strtotime('today')),
			'created_at'=>strftime('%Y-%m-%d %H:%M:%S',strtotime('today')),
			'updated_at'=>strftime('%Y-%m-%d %H:%M:%S',strtotime('today')),
			'deleted_at'=>strftime('%Y-%m-%d %H:%M:%S',strtotime('today')),
			]);
	}
}

Class CommissionTableSeeder extends Seeder {
	public function run() {
		DB::table('faceliftstaffcommission')->delete();


		FaceliftStaffCommission::create(
			[
			'staff_id'=>4321,
			'customer_id'=>'',
			'commission'=>'0.05',
			'services'=>'Ibeshe',
			'amount'=>'1000',
			'month'=>strftime('%m',strtotime('today')),
			'year'=>strftime('%Y',strtotime('today')),
			'created_at'=>strftime('%Y-%m-%d %H:%M:%S',strtotime('today')),
			'updated_at'=>strftime('%Y-%m-%d %H:%M:%S',strtotime('today')),
			]);
	}
}

Class ServicesTableSeeder extends Seeder {
	public function run() {
		DB::table('faceliftservices')->delete();


		FaceliftServices::create(
			[
			'service_type'=>'Haircut',
			'service_start'=>strftime('%H:%M:%S',strtotime('today')),
			'service_end'=>strftime('%H:%M:%S',strtotime('today')),
			'arrival'=>strftime('%H:%M:%S',strtotime('today')),
			'departure'=>strftime('%H:%M:%S',strtotime('today')),
			'rebooked_date'=>strftime('%Y-%m-%d',strtotime('today')),
			'attendant'=>'demo',
			'amount'=>rand(1000,4000),
			'client_id'=>1234,
			'staff_id'=>4321,
			'month'=>strftime('%m',strtotime('today')),
			'year'=>strftime('%Y',strtotime('today')),
			'created_at'=>strftime('%Y-%m-%d %H:%M:%S',strtotime('today')),
			'updated_at'=>strftime('%Y-%m-%d %H:%M:%S',strtotime('today')),
			]);
	}
}

Class ReferralsTableSeeder extends Seeder {
	public function run() {
		DB::table('faceliftreferrals')->delete();


		FaceliftReferrals::create(
			[
			'client_id'=>'1234',
			'staff_id'=>'4321',
			'month'=>strftime('%m',strtotime('today')),
			'year'=>strftime('%Y',strtotime('today')),
			'created_at'=>strftime('%Y-%m-%d %H:%M:%S',strtotime('today')),
			'updated_at'=>strftime('%Y-%m-%d %H:%M:%S',strtotime('today')),
			]);
	}
}

Class LpTableSeeder extends Seeder {
	public function run() {
		DB::table('faceliftlp')->delete();


		FaceliftLoyaltyPoints::create(
			[
			'client_id'=>'1234',
			'goal'=>'',
			'loyalty_points'=>50000,
			'month'=>strftime('%m',strtotime('today')),
			'year'=>strftime('%Y',strtotime('today')),
			'created_at'=>strftime('%Y-%m-%d %H:%M:%S',strtotime('today')),
			'updated_at'=>strftime('%Y-%m-%d %H:%M:%S',strtotime('today')),
			]);
	}
}