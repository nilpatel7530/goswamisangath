<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get India country ID
        $india = DB::table('country_manage')->where('name', 'India')->first();
        if (!$india) {
            $this->command->error('India country not found. Please run StatesSeeder first.');
            return;
        }
        $indiaId = $india->id;

        // Get all states
        $states = DB::table('state_master')
            ->where('country_id', $indiaId)
            ->get()
            ->keyBy('name');

        // Cities data organized by state
        $citiesByState = [
            'Andhra Pradesh' => [
                'Visakhapatnam', 'Vijayawada', 'Guntur', 'Nellore', 'Kurnool', 'Rajahmundry', 
                'Tirupati', 'Kakinada', 'Kadapa', 'Anantapur', 'Eluru', 'Ongole', 'Chittoor', 
                'Machilipatnam', 'Srikakulam', 'Adoni', 'Tenali', 'Proddatur', 'Chilakaluripet', 
                'Hindupur', 'Bhimavaram', 'Madanapalle', 'Guntakal', 'Dharmavaram', 'Gudivada'
            ],
            'Arunachal Pradesh' => [
                'Itanagar', 'Naharlagun', 'Pasighat', 'Namsai', 'Roing', 'Tezu', 'Ziro', 
                'Bomdila', 'Tawang', 'Khonsa', 'Aalo', 'Daporijo', 'Along', 'Seppa', 'Yingkiong'
            ],
            'Assam' => [
                'Guwahati', 'Silchar', 'Dibrugarh', 'Jorhat', 'Nagaon', 'Tinsukia', 'Tezpur', 
                'Bongaigaon', 'Dhubri', 'Diphu', 'North Lakhimpur', 'Karimganj', 'Goalpara', 
                'Sivasagar', 'Barpeta', 'Golaghat', 'Hailakandi', 'Mangaldoi', 'Morigaon', 
                'Kokrajhar', 'Hojai', 'Dhemaji', 'Lanka', 'Rangia', 'Numaligarh'
            ],
            'Bihar' => [
                'Patna', 'Gaya', 'Bhagalpur', 'Muzaffarpur', 'Purnia', 'Darbhanga', 'Arrah', 
                'Begusarai', 'Katihar', 'Munger', 'Chapra', 'Sasaram', 'Hajipur', 'Dehri', 
                'Bettiah', 'Siwan', 'Motihari', 'Nalanda', 'Buxar', 'Kishanganj', 'Jamalpur', 
                'Jehanabad', 'Aurangabad', 'Saharsa', 'Nawada', 'Madhepura'
            ],
            'Chhattisgarh' => [
                'Raipur', 'Bhilai', 'Bilaspur', 'Korba', 'Durg', 'Rajpur', 'Raigarh', 
                'Jagdalpur', 'Ambikapur', 'Chirmiri', 'Dhamtari', 'Mahasamund', 'Kanker', 
                'Janjgir', 'Kawardha', 'Sakti', 'Baloda Bazar', 'Bhatapara', 'Mungeli', 
                'Khairagarh', 'Kurud', 'Gariaband', 'Kondagaon', 'Narayanpur', 'Bijapur'
            ],
            'Goa' => [
                'Panaji', 'Margao', 'Vasco da Gama', 'Mapusa', 'Ponda', 'Mormugao', 
                'Curchorem', 'Bicholim', 'Valpoi', 'Sanguem', 'Canacona', 'Quepem', 
                'Pernem', 'Sattari', 'Tiswadi', 'Salcete', 'Bardez'
            ],
            'Gujarat' => [
                'Ahmedabad', 'Surat', 'Vadodara', 'Rajkot', 'Bhavnagar', 'Jamnagar', 
                'Gandhinagar', 'Junagadh', 'Gandhidham', 'Anand', 'Navsari', 'Morbi', 
                'Nadiad', 'Surendranagar', 'Bharuch', 'Mehsana', 'Bhuj', 'Porbandar', 
                'Palanpur', 'Valsad', 'Vapi', 'Gondal', 'Veraval', 'Godhra', 'Patan', 
                'Kalol', 'Dahod', 'Botad', 'Amreli', 'Himmatnagar'
            ],
            'Haryana' => [
                'Faridabad', 'Gurgaon', 'Panipat', 'Ambala', 'Yamunanagar', 'Rohtak', 
                'Hisar', 'Karnal', 'Sonipat', 'Panchkula', 'Sirsa', 'Bhiwani', 'Kaithal', 
                'Jind', 'Bahadurgarh', 'Rewari', 'Palwal', 'Thanesar', 'Fatehabad', 
                'Mahendragarh', 'Narnaul', 'Hansi', 'Tohana', 'Barwala', 'Narwana'
            ],
            'Himachal Pradesh' => [
                'Shimla', 'Mandi', 'Solan', 'Dharamshala', 'Kullu', 'Chamba', 'Palampur', 
                'Nahan', 'Bilaspur', 'Una', 'Hamirpur', 'Kangra', 'Kasauli', 'Manali', 
                'Dalhousie', 'Kotkhai', 'Rampur', 'Theog', 'Jogindernagar', 'Parwanoo', 
                'Sundernagar', 'Reckong Peo', 'Keylong', 'Kalpa', 'Narkanda'
            ],
            'Jharkhand' => [
                'Jamshedpur', 'Dhanbad', 'Ranchi', 'Bokaro Steel City', 'Deoghar', 'Hazaribagh', 
                'Giridih', 'Ramgarh', 'Medininagar', 'Chaibasa', 'Jhumri Telaiya', 'Sahibganj', 
                'Dumka', 'Phusro', 'Adityapur', 'Chatra', 'Gumla', 'Pakur', 'Simdega', 
                'Lohardaga', 'Latehar', 'Godda', 'Koderma', 'Jamtara', 'Sahebganj'
            ],
            'Karnataka' => [
                'Bangalore', 'Mysore', 'Hubli', 'Mangalore', 'Belagavi', 'Gulbarga', 
                'Davangere', 'Bellary', 'Bijapur', 'Shimoga', 'Tumkur', 'Raichur', 'Bidar', 
                'Hospet', 'Hassan', 'Udupi', 'Chitradurga', 'Kolar', 'Mandya', 'Chikmagalur', 
                'Gangavati', 'Bagalkot', 'Robertsonpet', 'Bhadravati', 'Chamrajnagar', 
                'Gokak', 'Karwar', 'Madikeri', 'Sindhnur', 'Yadgir'
            ],
            'Kerala' => [
                'Kochi', 'Kozhikode', 'Thiruvananthapuram', 'Thrissur', 'Malappuram', 
                'Kannur', 'Kollam', 'Alappuzha', 'Kottayam', 'Palakkad', 'Manjeri', 
                'Thalassery', 'Ponnani', 'Vatakara', 'Kanhangad', 'Payyannur', 'Koyilandy', 
                'Neyyattinkara', 'Kayamkulam', 'Nedumangad', 'Kattakkada', 'Changanassery', 
                'Kothamangalam', 'Muvattupuzha', 'Perumbavoor', 'Thodupuzha'
            ],
            'Madhya Pradesh' => [
                'Indore', 'Bhopal', 'Gwalior', 'Jabalpur', 'Raipur', 'Ujjain', 'Sagar', 
                'Ratlam', 'Satna', 'Rewa', 'Murwara', 'Singrauli', 'Burhanpur', 'Khandwa', 
                'Morena', 'Bhind', 'Chhindwara', 'Guna', 'Shivpuri', 'Vidisha', 'Chhatarpur', 
                'Damoh', 'Mandsaur', 'Khargone', 'Neemuch', 'Pithampur', 'Itarsi', 'Sehore', 
                'Betul', 'Seoni', 'Datia', 'Nagda', 'Dewas', 'Dhar', 'Mandla'
            ],
            'Maharashtra' => [
                'Mumbai', 'Pune', 'Nagpur', 'Thane', 'Nashik', 'Aurangabad', 'Solapur', 
                'Kalyan', 'Vasai-Virar', 'Navi Mumbai', 'Amravati', 'Kolhapur', 'Sangli', 
                'Jalgaon', 'Akola', 'Latur', 'Ahmednagar', 'Chandrapur', 'Parbhani', 
                'Ichalkaranji', 'Jalna', 'Bhusawal', 'Panvel', 'Satara', 'Beed', 'Yavatmal', 
                'Kamptee', 'Gondia', 'Barshi', 'Achalpur', 'Osmanabad', 'Nanded', 'Sindhudurg', 
                'Ratnagiri', 'Wardha', 'Udgir', 'Aurangabad', 'Jalna'
            ],
            'Manipur' => [
                'Imphal', 'Thoubal', 'Kakching', 'Lilong', 'Mayang Imphal', 'Yairipok', 
                'Bishnupur', 'Churachandpur', 'Ukhrul', 'Senapati', 'Tamenglong', 'Chandel', 
                'Jiribam', 'Kangpokpi', 'Moirang', 'Nambol', 'Sekmai', 'Wangjing', 
                'Wangoi', 'Yenning', 'Khongman', 'Lamshang', 'Lamlai', 'Konthoujam', 'Leimakhong'
            ],
            'Meghalaya' => [
                'Shillong', 'Tura', 'Jowai', 'Nongstoin', 'Baghmara', 'Williamnagar', 
                'Resubelpara', 'Mankachar', 'Ampati', 'Mairang', 'Mawkyrwat', 'Mawphlang', 
                'Cherrapunji', 'Nongpoh', 'Khliehriat', 'Mawla', 'Sohra', 'Mawshynrut', 
                'Mawryngkneng', 'Mawphlang', 'Mawkyrwat', 'Amlarem', 'Laskein', 'Thadlaskein'
            ],
            'Mizoram' => [
                'Aizawl', 'Lunglei', 'Saiha', 'Champhai', 'Kolasib', 'Serchhip', 
                'Lawngtlai', 'Mamit', 'Hnahthial', 'Khawzawl', 'Saitual', 'Khawbung', 
                'Vairengte', 'Thenzawl', 'Darlawn', 'Tlabung', 'Zawlnuam', 'Bilkhawthlir', 
                'Ngopa', 'Reiek', 'Saitual', 'Khawzawl', 'Hnahthial', 'Lunglei', 'Serchhip'
            ],
            'Nagaland' => [
                'Dimapur', 'Kohima', 'Mokokchung', 'Tuensang', 'Wokha', 'Mon', 'Zunheboto', 
                'Phek', 'Kiphire', 'Longleng', 'Peren', 'Chumukedima', 'Medziphema', 
                'Chozuba', 'Pfutsero', 'Meluri', 'Noklak', 'Shamator', 'Tizit', 'Tobu', 
                'Aboi', 'Akuluto', 'Atoizu', 'Bhandari', 'Changtongya', 'Chiephobozou'
            ],
            'Odisha' => [
                'Bhubaneswar', 'Cuttack', 'Rourkela', 'Berhampur', 'Sambalpur', 'Puri', 
                'Balasore', 'Bhadrak', 'Baripada', 'Balangir', 'Jharsuguda', 'Bargarh', 
                'Rayagada', 'Jeypore', 'Bhawanipatna', 'Dhenkanal', 'Angul', 'Kendujhar', 
                'Paradip', 'Barbil', 'Chhatrapur', 'Gopalpur', 'Jagatsinghpur', 'Kendrapara', 
                'Malkangiri', 'Nabarangpur', 'Nuapada', 'Nayagarh', 'Sundargarh', 'Talcher'
            ],
            'Punjab' => [
                'Ludhiana', 'Amritsar', 'Jalandhar', 'Patiala', 'Bathinda', 'Pathankot', 
                'Hoshiarpur', 'Mohali', 'Batala', 'Moga', 'Abohar', 'Sangrur', 'Barnala', 
                'Firozpur', 'Phagwara', 'Kapurthala', 'Muktsar', 'Rajpura', 'Fazilka', 
                'Gurdaspur', 'Malerkotla', 'Nabha', 'Nangal', 'Nawanshahr', 'Rupnagar', 
                'Samana', 'Sunam', 'Tarn Taran', 'Zira', 'Kotkapura'
            ],
            'Rajasthan' => [
                'Jaipur', 'Jodhpur', 'Kota', 'Bikaner', 'Ajmer', 'Udaipur', 'Bhilwara', 
                'Alwar', 'Bharatpur', 'Sikar', 'Pali', 'Tonk', 'Sawai Madhopur', 'Churu', 
                'Hanumangarh', 'Beawar', 'Baran', 'Banswara', 'Bundi', 'Chittorgarh', 
                'Dausa', 'Dholpur', 'Dungarpur', 'Ganganagar', 'Jaisalmer', 'Jalore', 
                'Jhalawar', 'Jhunjhunu', 'Karauli', 'Nagaur', 'Pratapgarh', 'Rajsamand', 
                'Sirohi', 'Sri Ganganagar', 'Barmer', 'Dungarpur'
            ],
            'Sikkim' => [
                'Gangtok', 'Namchi', 'Mangan', 'Gyalshing', 'Singtam', 'Rangpo', 'Jorethang', 
                'Rhenock', 'Rongli', 'Soreng', 'Melli', 'Ravangla', 'Pakyong', 'Chungthang', 
                'Lachung', 'Lachen', 'Pelling', 'Yuksom', 'Tumlong', 'Kabi', 'Rumtek', 
                'Martam', 'Temi', 'Rinchenpong', 'Pemayangtse'
            ],
            'Tamil Nadu' => [
                'Chennai', 'Coimbatore', 'Madurai', 'Tiruchirappalli', 'Salem', 'Tirunelveli', 
                'Tiruppur', 'Erode', 'Vellore', 'Thoothukudi', 'Dindigul', 'Thanjavur', 
                'Ranipet', 'Sivakasi', 'Karur', 'Udhagamandalam', 'Hosur', 'Nagercoil', 
                'Kanchipuram', 'Kumarapalayam', 'Karaikudi', 'Neyveli', 'Cuddalore', 
                'Kumbakonam', 'Tiruvannamalai', 'Pollachi', 'Rajapalayam', 'Gudiyatham', 
                'Pudukkottai', 'Vaniyambadi', 'Ambur', 'Nagapattinam', 'Tiruvallur'
            ],
            'Telangana' => [
                'Hyderabad', 'Warangal', 'Nizamabad', 'Karimnagar', 'Ramagundam', 'Khammam', 
                'Mahbubnagar', 'Nalgonda', 'Adilabad', 'Siddipet', 'Sangareddy', 'Miryalaguda', 
                'Jagtial', 'Mancherial', 'Bodhan', 'Kamareddy', 'Gadwal', 'Wanaparthy', 
                'Nagarkurnool', 'Narayanpet', 'Yadadri', 'Jangaon', 'Medak', 'Suryapet', 
                'Vikarabad', 'Jogulamba Gadwal', 'Rajanna Sircilla', 'Peddapalli', 'Jayashankar'
            ],
            'Tripura' => [
                'Agartala', 'Udaipur', 'Dharmanagar', 'Kailasahar', 'Belonia', 'Khowai', 
                'Teliamura', 'Ambassa', 'Kumarghat', 'Amarpur', 'Sabroom', 'Melaghar', 
                'Sonamura', 'Bishalgarh', 'Jirania', 'Dukli', 'Bishramganj', 'Kadamtala', 
                'Kamalpur', 'Karbook', 'Khowai', 'Longtharai Valley', 'Manu', 'Panisagar', 
                'Pecharthal', 'Sipahijala', 'Tulashikhar', 'Unakoti'
            ],
            'Uttar Pradesh' => [
                'Lucknow', 'Kanpur', 'Ghaziabad', 'Agra', 'Meerut', 'Varanasi', 'Allahabad', 
                'Bareilly', 'Aligarh', 'Moradabad', 'Saharanpur', 'Gorakhpur', 'Noida', 
                'Firozabad', 'Jhansi', 'Muzaffarnagar', 'Mathura', 'Rampur', 'Shahjahanpur', 
                'Farrukhabad', 'Fatehpur', 'Budaun', 'Hapur', 'Etawah', 'Mirzapur', 'Bulandshahr', 
                'Sambhal', 'Orai', 'Hardoi', 'Faizabad', 'Sitapur', 'Bahraich', 'Gonda', 
                'Deoria', 'Basti', 'Azamgarh', 'Mau', 'Jaunpur', 'Lalitpur', 'Banda', 'Pilibhit'
            ],
            'Uttarakhand' => [
                'Dehradun', 'Haridwar', 'Roorkee', 'Haldwani', 'Rudrapur', 'Kashipur', 
                'Rishikesh', 'Nainital', 'Almora', 'Pithoragarh', 'Mussoorie', 'Kotdwar', 
                'Srinagar', 'Ramnagar', 'Ranikhet', 'Champawat', 'Bageshwar', 'Chamoli', 
                'Tehri', 'Uttarkashi', 'Pauri', 'New Tehri', 'Gopeshwar', 'Joshimath', 
                'Khatima', 'Tanakpur', 'Sitarganj', 'Bajpur', 'Jaspur', 'Kichha'
            ],
            'West Bengal' => [
                'Kolkata', 'Howrah', 'Durgapur', 'Asansol', 'Siliguri', 'Bardhaman', 
                'Malda', 'Bahrampur', 'Jalpaiguri', 'Kharagpur', 'Cooch Behar', 'Darjeeling', 
                'Alipurduar', 'Purulia', 'Jangipur', 'Balurghat', 'Bankura', 'Adra', 
                'Habra', 'Haldia', 'Krishnanagar', 'Medinipur', 'Nabadwip', 'Raiganj', 
                'Santipur', 'Suri', 'Tamluk', 'Titagarh', 'Uluberia', 'Baranagar'
            ],
            // Union Territories
            'Andaman and Nicobar Islands' => [
                'Port Blair', 'Garacharma', 'Bambooflat', 'Prothrapur', 'Chouldhari', 
                'Ferrargunj', 'Bakultala', 'Wandoor', 'Mayabunder', 'Diglipur', 
                'Rangat', 'Hut Bay', 'Car Nicobar', 'Kamorta', 'Katchal', 'Nancowry', 
                'Teressa', 'Chowra', 'Little Andaman', 'Baratang', 'Long Island', 'Havelock'
            ],
            'Chandigarh' => [
                'Chandigarh', 'Manimajra', 'Burail', 'Daria', 'Kaimbwala', 'Kharar', 
                'Lalru', 'Mullanpur', 'Zirakpur', 'Derabassi', 'Panchkula', 'Mohali'
            ],
            'Dadra and Nagar Haveli and Daman and Diu' => [
                'Silvassa', 'Daman', 'Diu', 'Naroli', 'Dadra', 'Masat', 'Rakholi', 
                'Khanvel', 'Vapi', 'Amli', 'Kadaiya', 'Kherdi', 'Kudacha', 'Samarvani'
            ],
            'Delhi' => [
                'New Delhi', 'Delhi', 'North Delhi', 'South Delhi', 'East Delhi', 
                'West Delhi', 'Central Delhi', 'Noida', 'Gurgaon', 'Faridabad', 
                'Ghaziabad', 'Dwarka', 'Rohini', 'Pitampura', 'Laxmi Nagar', 'Karol Bagh', 
                'Connaught Place', 'Chanakyapuri', 'Vasant Kunj', 'Saket', 'Hauz Khas', 
                'Rajouri Garden', 'Janakpuri', 'Patel Nagar', 'Paschim Vihar'
            ],
            'Jammu and Kashmir' => [
                'Srinagar', 'Jammu', 'Anantnag', 'Baramulla', 'Sopore', 'Kathua', 
                'Udhampur', 'Rajouri', 'Poonch', 'Doda', 'Kishtwar', 'Ramban', 'Reasi', 
                'Samba', 'Ganderbal', 'Bandipora', 'Kupwara', 'Pulwama', 'Shopian', 
                'Kulgam', 'Budgam', 'Ganderbal', 'Leh', 'Kargil'
            ],
            'Ladakh' => [
                'Leh', 'Kargil', 'Nubra', 'Zanskar', 'Drass', 'Hemis', 'Diskit', 
                'Nyoma', 'Durbuk', 'Khaltsi', 'Turtuk', 'Panamik', 'Hunder', 'Sumur', 
                'Tegar', 'Chushul', 'Fukche', 'Hanle', 'Likir', 'Alchi', 'Basgo', 
                'Shey', 'Thiksey', 'Hemis', 'Stok'
            ],
            'Lakshadweep' => [
                'Kavaratti', 'Agatti', 'Amini', 'Andrott', 'Bitra', 'Chettat', 
                'Kadmat', 'Kalpeni', 'Kiltan', 'Minicoy', 'Bangaram', 'Thinnakara', 
                'Parali', 'Suheli', 'Cheriyam', 'Pitti', 'Kadmat', 'Amini', 'Kiltan'
            ],
            'Puducherry' => [
                'Puducherry', 'Karaikal', 'Mahe', 'Yanam', 'Ozhukarai', 'Villianur', 
                'Bahour', 'Nettapakkam', 'Mannadipet', 'Ariyankuppam', 'Embalam', 'Kurumbapet', 
                'Muthialpet', 'Reddiarpalayam', 'Saram', 'Thirubhuvanai', 'Uppalam', 'Veerampattinam'
            ],
        ];

        $totalCities = 0;
        foreach ($citiesByState as $stateName => $cities) {
            $state = $states->get($stateName);
            if (!$state) {
                $this->command->warn("State '{$stateName}' not found. Skipping cities.");
                continue;
            }

            foreach ($cities as $cityName) {
                $existing = DB::table('city_master')
                    ->where('name', $cityName)
                    ->orWhere('city_master', $cityName)
                    ->where('state_id', $state->id)
                    ->first();

                if (!$existing) {
                    DB::table('city_master')->insert([
                        'name' => $cityName,
                        'city_master' => $cityName,
                        'state_id' => $state->id,
                        'status' => 'active',
                        'is_visible' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $totalCities++;
                }
            }
        }

        $this->command->info("Successfully seeded {$totalCities} cities across all Indian states!");
    }
}
