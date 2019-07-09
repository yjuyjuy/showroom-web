<?php

use Illuminate\Database\Seeder;

class PricesTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$prices = [
				[748,1101181005,27,'{"M": "1250", "XS": "1250"}',1250,'2019-04-18 05:14:38',null],
				[749,1101181005,5,'{"M": "1180", "S": "1180", "XS": "1180", "XXS": "1180"}',1180,'2019-03-11 21:25:01',null],
				[751,1101181005,3,'{"M": "1300", "S": "1250", "XS": "1250", "XXS": "1250"}',1250,'2019-01-23 22:32:55',null],
				[752,1101161001,27,'{"L": "1100", "M": "1100", "S": "1100", "XL": "1100", "XS": "1100"}',1100,'2019-04-18 05:14:19',null],
				[753,1101161001,5,'{"L": "1150", "M": "1150", "S": "1150", "XL": "1150"}',1150,'2019-03-11 21:25:37',null],
				[754,1101191027,27,'{"S": "1200"}',1200,'2019-04-18 05:13:56',null],
				[756,1101191021,27,'{"M": "2000", "S": "2000", "XS": "2100"}',2000,'2019-04-18 05:13:26',null],
				[757,1101191019,27,'{"XS": "1300", "XXS": "1300"}',1300,'2019-04-18 05:13:04',null],
				[758,1101191019,12,'{"XS": "1500"}',1500,'2019-04-13 08:09:57',null],
				[760,1101191019,36,'{"XS": "1400", "XXS": "1400"}',1400,'2019-03-17 16:10:02',null],
				[761,1101191019,26,'{"XS": "1450", "XXS": "1450"}',1450,'2019-03-17 16:09:34',null],
				[767,1101191022,27,'{"M": "1200", "S": "1200", "XS": "1200", "XXS": "1200"}',1200,'2019-04-18 05:10:49',null],
				[772,1101181001,12,'{"XS": "999", "XXS": "999"}',999,'2019-04-13 08:10:14',null],
				[773,1101181001,9,'{"L": "1180", "M": "1180", "S": "1180", "XS": "1180", "XXS": "1180"}',1180,'2019-03-07 06:30:10',null],
				[774,1202181002,12,'{"L": "999", "M": "999", "S": "999"}',999,'2019-04-13 08:09:35',null],
				[776,1105162001,29,'{"L": "1800", "M": "1800"}',1800,'2019-01-26 08:55:57',null],
				[777,1101172002,12,'{"L": "1250", "S": "1250"}',1250,'2019-04-13 08:09:03',null],
				[778,1101172002,4,'{"L": "1300", "M": "1300", "S": "1300", "XS": "1300", "XXS": "1300"}',1300,'2019-04-13 07:19:12',null],
				[779,1101172002,5,'{"L": "1250", "M": "1250", "S": "1250", "XS": "1250"}',1250,'2019-03-16 05:17:49',null],
				[780,1101172001,12,'{"M": "1450", "S": "1450"}',1450,'2019-04-13 08:08:35',null],
				[781,1101172001,3,'{"L": "1550", "M": "1450", "S": "1450", "XXS": "1550"}',1450,'2019-03-27 01:20:54',null],
				[782,1201171001,9,'{"L": "1300", "M": "1300", "S": "1300"}',1300,'2019-04-13 07:55:30',null],
				[786,1105172004,9,'{"M": "2100", "S": "2100", "XS": "2100", "XXS": "2100"}',2100,'2019-04-13 07:48:27',null],
				[787,1103191005,9,'{"M": "2050", "S": "2050", "XS": "2050"}',2050,'2019-04-13 07:45:20',null],
				[788,1103191005,27,'{"S": "2200"}',2200,'2019-02-07 05:16:59',null],
				[792,1101161003,9,'{"S": "1300", "XL": "1300", "XS": "1300"}',1300,'2019-04-13 07:33:33',null],
				[793,1101161003,4,'{"XS": "900"}',900,'2019-01-14 03:40:46',null],
				[794,1101162002,9,'{"S": "1000", "XS": "1000", "XXS": "1000"}',1000,'2019-04-13 07:32:09',null],
				[795,1101162002,5,'{"L": "900", "M": "900", "S": "900", "XS": "900", "XXS": "900"}',900,'2019-03-11 21:14:30',null],
				[798,1101191004,1,'{"S": "1300"}',1300,'2019-03-27 04:23:57',null],
				[804,1101191003,1,'{"L": "1150"}',1150,'2019-01-12 05:07:23',null],
				[809,1106191009,26,'{"1": "3099", "2": "3299", "3": "3299"}',3099,'2019-03-28 01:33:31',null],
				[810,1106191009,1,'{"1": "3100"}',3100,'2019-03-27 19:13:23',null],
				[811,1106191009,19,'{"1": "3300"}',3300,'2019-03-27 05:41:15',null],
				[812,1106191001,1,'{"L": "2250", "M": "2150", "S": "2050", "XS": "1950", "XXS": "1850"}',1850,'2019-03-27 03:58:34',null],
				[816,1105191003,1,'{"M": "1900", "S": "1900"}',1900,'2019-03-27 04:26:13',null],
				[817,1105191003,4,'{"S": "1900"}',1900,'2019-03-27 03:51:18',null],
				[819,1105191003,9,'{"XS": "2400"}',2400,'2019-02-21 05:30:15',null],
				[821,1105191003,27,'{"L": "2300", "M": "2300", "S": "2300", "XL": "2300"}',2300,'2019-02-07 05:16:23',null],
				[826,1102191002,1,'{"XS": "980", "XXS": "980"}',980,'2019-03-27 04:09:29',null],
				[827,1102191003,1,'{"XS": "980", "XXS": "980"}',980,'2019-03-27 04:08:05',null],
				[828,2201192003,1,'{"OS": "300"}',300,'2019-03-27 04:01:56',null],
				[829,2201191003,1,'{"OS": "300"}',300,'2019-03-27 04:00:01',null],
				[832,1106191004,1,'{"M": "2250", "S": "2150", "XS": "2050"}',2050,'2019-03-27 03:57:08',null],
				[834,1106191004,33,'{"S": "2550", "XS": "2550"}',2550,'2019-02-07 05:48:27',null],
				[835,1106191004,27,'{"M": "2500", "S": "2500", "XS": "2500", "XXS": "2500"}',2500,'2019-02-07 05:18:03',null],
				[837,1101182007,32,'{"L": "1000", "M": "1000", "S": "1000", "XS": "1000", "XXS": "1000"}',1000,'2019-03-15 03:30:15',null],
				[838,1201151003,4,'{"L": "1400", "M": "1400", "S": "1400"}',1400,'2019-03-27 03:49:27',null],
				[839,1101181006,4,'{"M": "1100", "S": "1100"}',1100,'2019-03-27 03:48:52',null],
				[840,1101181006,3,'{"L": "1200", "M": "1200", "S": "1200", "XS": "1200", "XXS": "1200"}',1200,'2019-01-23 22:32:11',null],
				[844,1106162004,27,'{"S": "2300", "XS": "2300", "XXS": "2300"}',2300,'2019-01-28 07:49:23',null],
				[845,1101171002,3,'{"L": "1100", "M": "1100", "S": "1100", "XS": "1100", "XXS": "1100"}',1100,'2019-03-27 01:30:01',null],
				[846,1201151002,3,'{"L": "1200", "M": "1200", "S": "1200", "XL": "1200"}',1200,'2019-03-26 22:12:11',null],
				[847,1201151001,3,'{"L": "1200", "M": "1200", "S": "1200", "XL": "1200"}',1200,'2019-03-26 22:11:17',null],
				[849,1101181010,32,'{"S": "1050", "XXS": "1050"}',1050,'2019-03-16 05:43:47',null],
				[850,1101181010,9,'{"M": "1150", "S": "1150", "XS": "1150"}',1150,'2019-03-07 06:20:25',null],
				[853,1101151001,4,'{"XXS": "1200"}',1200,'2019-03-07 04:43:26',null],
				[855,1101182004,1,'{"S": "1150", "XS": "1233"}',1150,'2019-01-27 07:43:55',null],
				[857,1101191025,33,'{"M": "1150", "S": "1150", "XS": "1150", "XXS": "1150"}',1150,'2019-03-13 23:58:59',null],
				[858,1101191024,33,'{"M": "1150", "S": "1150", "XS": "1150", "XXS": "1150"}',1150,'2019-03-13 23:56:50',null],
				[859,2201182008,33,'{"41": "880", "42": "880", "43": "880", "44": "880", "45": "880"}',880,'2019-03-13 23:49:59',null],
				[860,1202191003,33,'{"L": "2000", "M": "2000", "S": "2000"}',2000,'2019-03-13 23:47:23',null],
				[861,1202191002,33,'{"L": "2000", "M": "2000", "S": "2000"}',2000,'2019-03-13 23:43:53',null],
				[862,1105191006,33,'{"L": "2050", "M": "2050", "S": "2050", "XL": "2050"}',2050,'2019-03-13 23:39:38',null],
				[863,1105191005,33,'{"M": "2950", "S": "2950", "XS": "2950", "XXS": "2950"}',2950,'2019-03-13 23:34:43',null],
				[864,1101161004,5,'{"L": "1150", "M": "1150", "S": "1150", "XS": "1150"}',1150,'2019-03-11 21:25:54',null],
				[867,1108161001,5,'{"M": "1680", "S": "1680", "XS": "1680"}',1680,'2019-03-11 21:19:30',null],
				[869,1108161001,9,'{"M": "1800", "S": "1800", "XS": "1800", "XXS": "1800"}',1800,'2019-01-26 04:28:12',null],
				[870,1105151003,5,'{"L": "1680"}',1680,'2019-03-11 21:18:45',null],
				[871,1105151003,9,'{"L": "1880", "M": "1880"}',1880,'2019-01-26 03:39:44',null],
				[872,1102142004,5,'{"L": "1160", "S": "1160"}',1160,'2019-03-11 21:18:12',null],
				[873,1102142003,5,'{"S": "1160"}',1160,'2019-03-11 21:18:02',null],
				[874,1102142002,5,'{"M": "1160", "XS": "1160"}',1160,'2019-03-11 21:17:33',null],
				[875,1102142001,5,'{"S": "1160"}',1160,'2019-03-11 21:17:07',null],
				[876,1203142001,5,'{"M": "1800", "S": "1500", "XS": "1500", "XXS": "1500"}',1500,'2019-03-11 21:13:42',null],
				[878,1103172002,27,'{"L": "1800", "M": "1800", "S": "1800", "XL": "1800", "XS": "1800", "XXS": "1800"}',1800,'2019-03-07 07:28:16',null],
				[879,1101191008,25,'{"L": "1350", "M": "1350", "S": "1350", "XS": "1350"}',1350,'2019-03-07 07:21:29',null],
				[882,1105171002,27,'{"L": "2300", "M": "2150", "S": "2150", "XL": "2300", "XS": "2000", "XXS": "2000"}',2000,'2019-03-07 06:56:01',null],
				[883,2201192002,14,'{"39": "850", "40": "850", "41": "850"}',850,'2019-03-07 06:48:24',null],
				[885,1101191005,1,'{"S": "1050", "XS": "1050"}',1050,'2019-02-15 06:33:43',null],
				[888,1105172002,29,'{"XS": "2300", "XXS": "2300"}',2300,'2019-03-07 06:22:58',null],
				[889,1105172002,27,'{"L": "2400", "M": "2400"}',2400,'2019-03-03 18:53:43',null],
				[893,1101152001,4,'{"S": "999", "XS": "999", "XXS": "999"}',999,'2019-03-07 05:28:08',null],
				[894,1101152001,9,'{"M": "999", "S": "999", "XS": "999", "XXS": "999"}',999,'2019-01-26 04:30:37',null],
				[898,1101191006,4,'{"S": "1200"}',1200,'2019-03-07 05:09:01',null],
				[899,1103191004,4,'{"S": "1900", "XL": "1900"}',1900,'2019-03-07 05:08:12',null],
				[900,1103182004,4,'{"S": "1280", "XS": "1280"}',1280,'2019-03-07 05:02:09',null],
				[901,1106191003,4,'{"L": "2300", "M": "2300", "S": "2300", "XL": "2300", "XS": "2300"}',2300,'2019-03-07 04:56:48',null],
				[902,1106191003,9,'{"S": "2400", "XL": "2400", "XS": "2400"}',2400,'2019-02-15 06:58:00',null],
				[906,1202181004,1,'{"L": "1800", "M": "1800", "XS": "1800"}',1800,'2019-03-06 06:49:07',null],
				[907,1104172001,1,'{"XXS": "4200"}',4200,'2019-03-06 06:46:48',null],
				[908,1203191001,25,'{"OS": "2400"}',2400,'2019-03-06 05:56:45',null],
				[910,1202172001,27,'{"L": "2100", "M": "1950", "S": "1800", "XL": "2100", "XS": "1800", "XXS": "1800"}',1800,'2019-03-05 06:23:27',null],
				[911,1105172001,27,'{"L": "2500", "M": "2500", "XXS": "2100"}',2100,'2019-03-03 18:53:37',null],
				[912,1101191020,26,'{"S": "1199"}',1199,'2019-03-01 07:55:53',null],
				[913,1202182001,1,'{"M": "1800"}',1800,'2019-02-25 04:33:27',null],
				[915,1101191018,35,'{"XL": "1399", "XS": "1399"}',1399,'2019-02-24 07:17:38',null],
				[917,2201181008,1,'{"OS": "300"}',300,'2019-02-24 06:24:27',null],
				[918,2201181006,1,'{"OS": "300"}',300,'2019-02-24 06:17:34',null],
				[919,1105191004,9,'{"L": "2500", "M": "2500"}',2500,'2019-02-21 05:30:31',null],
				[923,1101191016,19,'{"L": "1350", "M": "1350", "S": "1350", "XL": "1350", "XS": "1350"}',1350,'2019-02-21 03:40:22',null],
				[924,1103191008,28,'{"XS": "1580"}',1580,'2019-02-15 07:16:38',null],
				[925,1103191008,27,'{"L": "2200", "M": "2200", "S": "2200", "XL": "2200", "XS": "2200"}',2200,'2019-02-07 05:16:42',null],
				[926,1103191008,33,'{"L": "1950", "M": "1950", "S": "1950", "XL": "1950"}',1950,'2019-01-28 08:27:11',null],
				[927,1104191001,28,'{"L": "3580"}',3580,'2019-02-15 07:15:45',null],
				[928,1102182001,9,'{"S": "1100", "XS": "1100", "XXS": "1100"}',1100,'2019-02-15 06:57:16',null],
				[929,1101181008,9,'{"S": "1300", "XS": "1300"}',1300,'2019-02-15 06:48:21',null],
				[930,1105171003,9,'{"S": "2200", "XS": "2200"}',2200,'2019-02-15 06:46:17',null],
				[931,1105171003,1,'{"M": "1980"}',1980,'2019-01-12 05:15:58',null],
				[932,1106182004,9,'{"S": "1700"}',1700,'2019-02-15 06:43:04',null],
				[933,1101142002,9,'{"M": "1100", "XXS": "1100"}',1100,'2019-02-15 06:42:27',null],
				[934,1101142002,1,'{"M": "900"}',900,'2019-01-12 04:54:54',null],
				[935,1101142003,9,'{"S": "1100", "XXS": "1100"}',1100,'2019-02-15 06:42:09',null],
				[936,1101142003,1,'{"S": "900"}',900,'2019-01-12 04:56:25',null],
				[937,1101142001,9,'{"XXS": "1050"}',1050,'2019-02-15 06:42:00',null],
				[938,1105182001,26,'{"XS": "1699", "XXS": "1699"}',1699,'2019-02-08 05:33:27',null],
				[939,1105182001,1,'{"M": "2230"}',2230,'2019-01-12 05:19:07',null],
				[940,1101182012,26,'{"XS": "999"}',999,'2019-02-08 05:33:05',null],
				[941,1103191007,33,'{"L": "2150", "M": "2150", "S": "2150", "XL": "2150", "XXL": "2150"}',2150,'2019-02-07 05:46:34',null],
				[942,2201182007,33,'{"40": "780", "41": "780", "42": "780", "43": "780", "44": "780", "45": "780"}',780,'2019-02-07 05:40:12',null],
				[944,1105181006,29,'{"L": "1700", "M": "1700", "S": "1700", "XS": "1700", "XXS": "1700"}',1700,'2019-01-26 08:57:59',null],
				[945,1105181006,9,'{"L": "2150", "M": "2150", "S": "2150"}',2150,'2019-01-26 03:43:32',null],
				[946,1103191006,27,'{"L": "2200", "M": "2200", "S": "2200", "XL": "2200", "XS": "2200", "XXS": "2200"}',2200,'2019-02-07 05:18:32',null],
				[947,1105191002,27,'{"L": "2300", "M": "2300", "S": "2300", "XL": "2300"}',2300,'2019-02-07 05:16:16',null],
				[948,1106191007,9,'{"L": "2600", "XL": "2600"}',2600,'2019-02-07 04:58:56',null],
				[950,1103182001,27,'{"S": "1950", "XS": "1950", "XXS": "1950"}',1950,'2019-01-28 08:45:59',null],
				[954,2201191006,33,'{"OS": "700"}',700,'2019-01-28 08:32:51',null],
				[957,1101191014,33,'{"M": "1150", "S": "1150", "XS": "1150", "XXS": "1150"}',1150,'2019-01-28 08:22:30',null],
				[959,2101191002,33,'{"OS": "1950"}',1950,'2019-01-28 08:17:10',null],
				[962,2201182005,1,'{"OS": "400"}',400,'2019-01-28 08:13:48',null],
				[963,2201182004,1,'{"OS": "350"}',350,'2019-01-28 08:13:44',null],
				[964,2201182003,1,'{"OS": "350"}',350,'2019-01-28 08:13:39',null],
				[965,2201182002,1,'{"OS": "400"}',400,'2019-01-28 08:13:30',null],
				[966,2201191004,33,'{"OS": "400"}',400,'2019-01-28 08:13:03',null],
				[968,1101182009,1,'{"L": "950", "M": "950", "S": "950", "XS": "950", "XXS": "1000"}',950,'2019-01-28 08:05:26',null],
				[969,1101182008,1,'{"L": "900", "M": "900", "S": "900", "XS": "900", "XXS": "950"}',900,'2019-01-28 08:05:18',null],
				[970,1106182002,27,'{"S": "2100", "XS": "2100"}',2100,'2019-01-28 07:44:40',null],
				[971,1202181001,1,'{"S": "1800"}',1800,'2019-01-27 10:41:35',null],
				[972,1101182010,32,'{"S": "888", "XS": "888", "XXS": "888"}',888,'2019-01-27 09:43:01',null],
				[975,1106181003,29,'{"L": "2300", "M": "2300", "S": "2300", "XS": "2300"}',2300,'2019-01-26 08:57:42',null],
				[976,1105142003,29,'{"M": "1600", "S": "1400", "XS": "1400"}',1400,'2019-01-26 08:55:14',null],
				[977,1105151004,29,'{"L": "2050", "M": "2050"}',2050,'2019-01-26 08:54:16',null],
				[978,1106162003,29,'{"L": "2280", "M": "2280", "S": "2280", "XS": "2280", "XXS": "2280"}',2280,'2019-01-26 08:53:09',null],
				[979,1101162001,9,'{"XL": "1300", "XXS": "1300"}',1300,'2019-01-26 04:30:52',null],
				[980,1101162001,3,'{"M": "1450", "S": "1450", "XS": "1450"}',1450,'2019-01-23 22:33:59',null],
				[981,1106162002,9,'{"S": "2000", "XS": "2000"}',2000,'2019-01-26 04:29:26',null],
				[984,1106152001,9,'{"XS": "2150"}',2150,'2019-01-23 02:57:59',null],
				[985,1202151001,5,'{"S": "1780", "XS": "1780"}',1780,'2019-01-22 09:27:36',null],
				[986,1102172001,3,'{"M": "1250"}',1250,'2019-01-22 09:05:29',null],
				[987,2201181003,4,'{"OS": "499"}',499,'2019-01-14 03:50:12',null],
				[989,1108162001,1,'{"M": "3000", "S": "3000"}',3000,'2019-01-12 05:22:12',null],
				[990,1108142002,1,'{"XS": "3800"}',3800,'2019-01-12 05:21:33',null],
				[991,1105181002,1,'{"XS": "1730"}',1730,'2019-01-12 05:17:02',null],
				[992,1105172005,1,'{"S": "1815", "XXS": "1730"}',1730,'2019-01-12 05:16:35',null],
				[993,1105171001,1,'{"M": "1980"}',1980,'2019-01-12 05:12:53',null],
				[994,1105151002,1,'{"XS": "1650"}',1650,'2019-01-12 05:12:16',null],
				[995,1103181002,1,'{"L": "1980", "S": "1980", "XS": "1980"}',1980,'2019-01-12 05:10:24',null],
				[996,1101182001,1,'{"M": "900"}',900,'2019-01-12 05:02:55',null],
				[997,2201182007,4,'{"40": "800", "41": "800", "42": "800", "43": "800"}',800,'2019-05-28 03:17:56',null],
				[998,2201182008,4,'{"41": "850", "42": "850"}',850,'2019-05-28 03:18:51',null],
				[999,1105191007,4,'{"M": "2200"}',2200,'2019-05-28 03:20:48',null],
				[1000,1105191008,4,'{"L": "2200", "M": "2200", "S": "2200", "XL": "2200"}',2200,'2019-05-28 03:26:27',null],
				[1001,1103191011,4,'{"L": "2000", "M": "2000", "S": "2000"}',2000,'2019-05-28 03:30:04',null],
				[1002,1101191022,4,'{"M": "1200", "S": "1200", "XS": "1200", "XXS": "1200"}',1200,'2019-05-28 03:30:33',null],
				[1003,1101191024,4,'{"M": "1200", "S": "1200", "XS": "1200", "XXS": "1200"}',1200,'2019-05-28 03:36:57',null],
				[1004,1101181005,9,'{"M": "1300"}',1300,'2019-05-28 03:49:11',null],
				[1005,1103191012,9,'{"M": "2150", "S": "2150", "XL": "2150"}',2150,'2019-05-28 03:51:59',null],
				[1006,1105191008,9,'{"L": "2150", "M": "2150", "S": "2150", "XL": "2150"}',2150,'2019-05-28 03:52:28',null],
				[1007,1105191007,9,'{"M": "2150", "S": "2150"}',2150,'2019-05-28 03:52:43',null],
				[1009,1101191019,9,'{"XS": "1300"}',1300,'2019-05-28 03:54:59',null],
				[1010,1101182007,9,'{"S": "1150", "XS": "1150", "XXS": "1150"}',1150,'2019-05-28 03:55:38',null],
				[1011,1101162003,9,'{"M": "1300", "S": "1300", "XS": "1300", "XXS": "1300"}',1300,'2019-05-28 03:56:00',null],
				[1012,1101172004,9,'{"L": "1150", "M": "1150", "XXS": "1150"}',1150,'2019-05-28 03:56:44',null],
				[1013,1201151003,9,'{"M": "1450", "S": "1450"}',1450,'2019-05-28 03:57:55',null],
				[1014,1201182001,9,'{"L": "1400", "M": "1400", "S": "1400", "XL": "1400"}',1400,'2019-05-28 04:01:21',null],
				[1015,1105172002,9,'{"XS": "2500"}',2500,'2019-05-28 04:04:53',null],
				[1016,1101161004,32,'{"M": "999", "S": "999", "XL": "999"}',999,'2019-05-28 04:24:58',null],
				[1017,1101161001,32,'{"L": "999", "M": "999", "S": "999", "XL": "999"}',999,'2019-05-28 04:25:16',null],
				[1018,1103181005,32,'{"S": "1599", "XS": "1599", "XXS": "1599"}',1599,'2019-05-28 04:26:49',null],
				[1019,1102181001,32,'{"XS": "799"}',799,'2019-05-28 04:27:27',null],
				[1020,1102182001,32,'{"XS": "800"}',800,'2019-05-28 04:27:55',null],
				[1021,1101191019,32,'{"XS": "1150"}',1150,'2019-05-28 04:28:34',null],
				[1022,1101162002,32,'{"L": "900", "M": "900", "S": "900", "XS": "900", "XXS": "900"}',900,'2019-05-28 04:33:12',null],
				[1023,1101172002,32,'{"L": "1200", "S": "1200", "XS": "1200", "XXS": "1200"}',1200,'2019-05-28 04:33:45',null],
				[1024,1101142001,3,'{"XS": "1200", "XXS": "1200"}',1200,'2019-05-28 23:13:07',null],
				[1025,1101162004,3,'{"L": "1400", "M": "1400", "S": "1400", "XS": "1400"}',1400,'2019-05-29 09:29:09',null],
				[1026,1101191004,12,'{"L": "1400", "M": "1400", "S": "1400"}',1400,'2019-05-29 10:00:29',null],
				[1028,1101171001,12,'{"L": "1200", "M": "1200"}',1200,'2019-05-29 10:02:42',null],
				[1029,1101191003,12,'{"L": "1300", "S": "1300"}',1300,'2019-05-29 10:04:01',null],
				[1030,1101191003,3,'{"S": "1300"}',1300,'2019-05-29 10:06:43',null],
				[1031,2201182007,14,'{"39": "850", "40": "850", "41": "850", "42": "850", "43": "850", "44": "850"}',850,'2019-05-29 11:03:16',null],
				[1032,1101191021,14,'{"M": "1900", "S": "1900", "XS": "1900", "XXS": "1900"}',1900,'2019-05-29 11:03:48',null],
				[1033,1101191028,14,'{"M": "1400", "S": "1400", "XS": "1400", "XXS": "1400"}',1400,'2019-05-29 11:07:09',null],
				[1034,1101191029,14,'{"M": "1400", "S": "1400", "XS": "1400", "XXS": "1400"}',1400,'2019-05-29 11:11:16',null],
				[1035,1101191020,14,'{"M": "1300", "S": "1300", "XS": "1300", "XXS": "1300"}',1300,'2019-05-29 11:11:44',null],
				[1036,1101191018,14,'{"L": "1400", "M": "1400", "S": "1400"}',1400,'2019-05-29 11:12:02',null],
				[1038,1101191019,14,'{"M": "1300", "S": "1300", "XS": "1300", "XXS": "1300"}',1300,'2019-05-29 11:13:04',null],
				[1039,1107191003,14,'{"M": "3000", "S": "3000"}',3000,'2019-05-29 11:13:43',null],
				[1040,1101191030,14,'{"L": "2000"}',2000,'2019-05-29 11:16:08',null],
				[1041,1105191005,14,'{"M": "3000", "S": "3000", "XS": "3000", "XXS": "3000"}',3000,'2019-05-29 11:16:42',null],
				[1042,1105191010,14,'{"L": "2200", "M": "2200", "S": "2200"}',2200,'2019-05-29 11:21:04',null],
				[1043,1101182009,26,'{"M": "999", "S": "999", "XS": "999"}',999,'2019-05-29 11:30:19',null],
				[1044,1101191022,26,'{"S": "1199", "XS": "1199"}',1199,'2019-05-29 11:30:41',null],
				[1045,1105191011,26,'{"L": "1999", "M": "1999", "S": "1999", "XS": "1999"}',1999,'2019-05-29 11:35:39',null],
				[1046,1103191013,26,'{"L": "1899", "M": "1899", "S": "1899", "XS": "1899"}',1899,'2019-05-29 11:37:00',null],
				[1047,1103191007,26,'{"M": "1899"}',1899,'2019-05-29 11:37:26',null],
				[1048,1202191004,33,'{"M": "2000", "S": "2000", "XS": "2000"}',2000,'2019-05-29 11:51:49',null],
				[1049,1201191002,33,'{"M": "1150", "S": "1150", "XS": "1000"}',1000,'2019-05-29 11:54:42',null],
				[1050,1201191003,33,'{"L": "1150", "M": "1150", "S": "1150", "XS": "1000"}',1000,'2019-05-29 11:55:43',null],
				[1052,1201191001,33,'{"M": "1150", "S": "1150", "XS": "950", "XXS": "950"}',950,'2019-05-29 11:57:54',null],
				[1053,1105191011,33,'{"M": "2050", "S": "2050", "XS": "1650", "XXS": "1650"}',1650,'2019-05-29 11:58:54',null],
				[1054,1105191008,33,'{"L": "2050", "M": "2050", "S": "2050", "XL": "2050"}',2050,'2019-05-29 11:59:38',null],
				[1055,1105191007,33,'{"L": "2050", "M": "2050", "S": "2050", "XL": "2050"}',2050,'2019-05-29 12:00:12',null],
				[1056,1106191010,33,'{"L": "2350", "M": "2350", "S": "2350", "XL": "2350"}',2350,'2019-05-29 12:03:40',null],
				[1057,1101191021,33,'{"XXS": "1750"}',1750,'2019-05-29 12:05:26',null],
				[1058,1101191019,33,'{"XXS": "1350"}',1350,'2019-05-29 12:05:47',null],
				[1059,1101191020,33,'{"XXS": "1250"}',1250,'2019-05-29 12:06:17',null],
				[1061,1101191015,33,'{"M": "1150", "S": "1150", "XS": "1150", "XXS": "1150"}',1150,'2019-05-29 12:07:32',null],
				[1062,1101191031,33,'{"L": "950", "M": "950", "S": "950", "XL": "950"}',950,'2019-05-29 12:09:26',null],
				[1063,1101191027,33,'{"M": "1150", "S": "1150", "XS": "1150", "XXS": "1150"}',1150,'2019-05-29 12:09:44',null],
				[1064,1101191022,33,'{"M": "1150", "S": "1150", "XS": "1150", "XXS": "1150"}',1150,'2019-05-29 12:09:59',null],
				[1065,2201192004,33,'{"OS": "380"}',380,'2019-05-29 12:13:08',null],
				[1066,2201192005,33,'{"OS": "380"}',380,'2019-05-29 12:14:11',null],
				[1067,2201192006,33,'{"OS": "450"}',450,'2019-05-29 12:16:05',null],
				[1068,2201192007,33,'{"OS": "450"}',450,'2019-05-29 12:16:57',null],
				[1069,2201192008,33,'{"OS": "450"}',450,'2019-05-29 12:18:02',null],
				[1070,2101191003,33,'{"OS": "2150"}',2150,'2019-05-29 13:01:42',null],
				[1071,2101191004,33,'{"OS": "2150"}',2150,'2019-05-29 13:02:30',null],
				[1072,2201192009,33,'{"OS": "680"}',680,'2019-05-29 13:08:39',null],
				[1073,2201192010,33,'{"OS": "280"}',280,'2019-05-29 13:12:04',null],
				[1074,1101191032,33,'{"XXS": "1350"}',1350,'2019-05-29 13:15:14',null],
				[1075,1103191002,33,'{"L": "1650", "M": "1650", "S": "1650", "XL": "1650"}',1650,'2019-05-29 13:51:11',null],
				[1076,1103191009,33,'{"L": "1650", "M": "1650", "S": "1650", "XL": "1650"}',1650,'2019-05-29 13:52:55',null],
				[1077,1101191005,9,'{"L": "1100", "M": "1100", "S": "1100", "XS": "1100"}',1100,'2019-05-29 14:04:57',null],
				[1079,1103191010,33,'{"L": "1750", "M": "1750", "S": "1750", "XL": "1750"}',1750,'2019-05-29 14:08:27',null],
				[1080,1105191001,33,'{"L": "2000", "M": "2000", "S": "2000", "XL": "2000"}',2000,'2019-05-29 14:08:43',null],
				[1081,1105191004,33,'{"L": "2100", "M": "2100", "S": "2100", "XL": "2100"}',2100,'2019-05-29 14:08:56',null],
				[1082,1106191005,33,'{"L": "2350", "M": "2350", "S": "2350", "XL": "2350"}',2350,'2019-05-29 14:09:10',null],
				[1083,1106191006,33,'{"L": "2350", "M": "2350", "S": "2350", "XL": "2350"}',2350,'2019-05-29 14:09:23',null],
				[1084,2201191005,33,'{"OS": "950"}',950,'2019-05-29 14:09:31',null],
				[1085,2201192001,33,'{"OS": "850"}',850,'2019-05-29 14:09:41',null],
				[1086,2201191003,33,'{"OS": "350"}',350,'2019-05-29 14:09:55',null],
				[1088,1101182004,5,'{"M": "1250", "S": "1300", "XL": "1250"}',1250,'2019-05-30 04:05:04',null],
				[1089,1101181006,5,'{"M": "1080", "S": "1080", "XXS": "1150"}',1080,'2019-05-30 04:06:36',null],
				[1090,1101182007,5,'{"M": "1100", "S": "1100", "XS": "1100"}',1100,'2019-05-30 04:06:59',null],
				[1091,1101191009,5,'{"L": "1300", "M": "1300", "XL": "1300"}',1300,'2019-05-30 04:09:50',null],
				[1092,1101181009,1,'{"XS": "1300"}',1300,'2019-05-30 09:46:46',null],
				[1094,2201181002,1,'{"OS": "300"}',300,'2019-05-30 10:02:17',null],
				[1095,1103172001,1,'{"XS": "1500"}',1500,'2019-05-30 10:06:13',null],
				[1096,1103172002,1,'{"S": "1800"}',1800,'2019-05-30 10:06:26',null],
				[1097,1103191007,1,'{"S": "1900"}',1900,'2019-05-30 10:06:51',null],
				[1098,1101191017,1,'{"L": "1300", "S": "1300"}',1300,'2019-05-30 10:26:24',null],
				[1099,1101191018,1,'{"L": "1300", "M": "1300", "XL": "1300"}',1300,'2019-05-30 10:32:32',null],
				[1100,1101191015,1,'{"XS": "1050"}',1050,'2019-05-30 10:33:05',null],
				[1101,1101191017,35,'{"S": "1399", "XS": "1399"}',1399,'2019-05-30 11:08:21',null],
				[1102,1101182005,1,'{"M": "1250", "S": "1300"}',1250,'2019-05-30 15:53:01',null],
				[1103,2201191007,38,'{"39": "1300", "40": "1300", "41": "1300"}',1300,'2019-05-31 12:15:09',null],
				[1105,1101191027,25,'{"L": "1350", "M": "1350", "S": "1350", "XS": "1350"}',1350,'2019-05-31 12:18:39',null],
				[1106,1101191022,25,'{"L": "1250", "M": "1250", "S": "1250", "XS": "1250", "XXS": "1250"}',1250,'2019-05-31 12:19:12',null],
				[1107,1101191024,25,'{"M": "1250", "S": "1250", "XS": "1250", "XXS": "1250"}',1250,'2019-05-31 12:19:23',null],
				[1108,1101191033,25,'{"L": "1350", "M": "1350", "S": "1350", "XS": "1350"}',1350,'2019-05-31 12:23:17',null],
				[1109,1105191007,35,'{"L": "1999", "M": "1999", "S": "1999", "XL": "1999"}',1999,'2019-06-02 11:21:46',null],
				[1110,1105191008,35,'{"L": "1999", "M": "1999", "S": "1999", "XL": "1999"}',1999,'2019-06-02 11:22:02',null],
				[1111,1103182003,35,'{"S": "800", "XS": "800"}',800,'2019-06-02 11:22:51',null],
				[1112,1105181007,35,'{"M": "800", "S": "800", "XS": "800", "XXS": "800"}',800,'2019-06-02 11:23:26',null],
				[1113,1101191016,35,'{"L": "1299", "M": "1299", "S": "1299", "XS": "1299", "XXS": "1299"}',1299,'2019-06-02 11:24:23',null],
				[1114,1101191015,35,'{"L": "1299", "M": "1299", "S": "1299", "XS": "1299", "XXS": "1299"}',1299,'2019-06-02 11:24:35',null],
				[1115,1101191005,35,'{"L": "999", "M": "999", "S": "999", "XL": "999"}',999,'2019-06-02 11:24:48',null],
				[1116,1101182005,39,'{"M": "1350", "XS": "1350"}',1350,'2019-06-02 11:27:08',null],
				[1117,1101182007,42,'{"L": "1080", "M": "1080", "S": "1080", "XS": "1080", "XXS": "1080"}',1080,'2019-06-02 11:39:49',null],
				[1118,1101191003,42,'{"L": "1400", "M": "1400", "S": "1400", "XS": "1400"}',1400,'2019-06-02 11:40:11',null],
				[1119,1101191005,42,'{"M": "980", "S": "980", "XS": "980", "XXS": "980"}',980,'2019-06-02 11:40:28',null],
				[1120,1101191014,42,'{"M": "980", "S": "980", "XS": "980", "XXS": "980"}',980,'2019-06-02 11:40:41',null],
				[1121,1101172001,42,'{"M": "1400", "S": "1450", "XXS": "1400"}',1400,'2019-06-02 11:41:33',null],
				[1122,1106162005,42,'{"S": "2400", "XS": "2400", "XXS": "2400"}',2400,'2019-06-02 11:41:56',null],
				[1123,1104182001,42,'{"S": "4300", "XS": "4300", "XXS": "4300"}',4300,'2019-06-02 11:43:39',null],
				[1124,1101181006,42,'{"M": "1100", "S": "1100", "XS": "1100", "XXS": "1100"}',1100,'2019-06-02 11:44:03',null],
				[1125,1202182001,42,'{"L": "1780", "S": "1780"}',1780,'2019-06-02 11:44:36',null],
				[1126,1101162005,42,'{"M": "1100", "XS": "1100", "XXS": "1100"}',1100,'2019-06-02 11:46:09',null],
				[1127,1202172002,42,'{"XS": "1550", "XXS": "1550"}',1550,'2019-06-02 11:50:34',null],
				[1128,1102162001,42,'{"S": "888", "XS": "800", "XXS": "800"}',800,'2019-06-02 11:52:37',null],
				[1129,1101161001,42,'{"M": "1100", "S": "1100", "XS": "1100"}',1100,'2019-06-02 12:14:23',null],
				[1130,1107191004,43,'{"M": "3200"}',3200,'2019-06-02 12:16:22',null],
				[1131,1101191016,43,'{"M": "1300", "S": "1300", "XS": "1300"}',1300,'2019-06-02 12:17:25',null],
				[1132,1101191015,43,'{"M": "1300", "S": "1300", "XS": "1300"}',1300,'2019-06-02 12:17:35',null],
				[1133,1101191032,43,'{"XS": "1400", "XXS": "1400"}',1400,'2019-06-02 12:17:54',null],
				[1134,2102191001,44,'{"OS": "2100"}',2100,'2019-06-02 12:38:36',null],
				[1135,2102191002,44,'{"OS": "2100"}',2100,'2019-06-02 12:39:15',null],
				[1136,1101191030,25,'{"M": "1900", "S": "1900"}',1900,'2019-06-03 14:08:41',null],
				[1137,1101191017,25,'{"S": "1400", "XS": "1400", "XXS": "1400"}',1400,'2019-06-03 14:09:21',null]
			];
		foreach ($prices as $row) {
			$data = [];
			foreach (json_decode($row[3], true) as $size => $value) {
				$data[] = [ 'size' => $size, 'cost' => $value, 'resell' => $value, 'retail' => ceil($value * 0.12)*10];
			}
			$data = Arr::sort($data, function ($row) {
				return array_search($row['size'], ['XXS','XS','S','M','L','XL','XXL']);
			});
			$price = new \App\Price();
			$price->id = $row[0];
			$price->vendor_id = $row[2];
			$price->product_id = $row[1];
			$price->data = $data;
			$price->save();
		}
	}
}
