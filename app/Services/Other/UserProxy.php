<?php
// +----------------------------------------------------------------------
// | Author: zhudongchang
// +----------------------------------------------------------------------
// | Decsription:摇篮网1.service 用户信息curd类
// +----------------------------------------------------------------------
// | Date:	2015-6-12
// +----------------------------------------------------------------------
namespace App\Services\Other;
class UserProxy {
	//1.service.yaolanc.com 所有表结构的序列化，如果表结构有改变(增加表、或已有表的主键名字有改变 其他改变不影响本服务的使用)需要重新序列化到此处，否则有可能影响相应表的curd
	private static $tableDescSerialize=<<<EOT
a:32:{s:11:"BoroughList";a:3:{i:0;a:6:{s:5:"Field";s:9:"BoroughId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:6:"CityId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:11:"BoroughName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:9:"ChildInfo";a:14:{i:0;a:6:{s:5:"Field";s:6:"UserId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:4:"Name";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:8:"NickName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:3;a:6:{s:5:"Field";s:9:"BloodType";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:4;a:6:{s:5:"Field";s:6:"Gender";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:5;a:6:{s:5:"Field";s:14:"BirthCountryId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:6;a:6:{s:5:"Field";s:15:"BirthProvinceId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:7;a:6:{s:5:"Field";s:11:"BirthCityId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:8;a:6:{s:5:"Field";s:14:"BirthBoroughId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:9;a:6:{s:5:"Field";s:9:"BirthDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:10;a:6:{s:5:"Field";s:21:"BirthdayModifiedCount";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:11;a:6:{s:5:"Field";s:12:"ModifiedDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:12;a:6:{s:5:"Field";s:14:"IsDefaultChild";s:4:"Type";s:7:"int(11)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";s:1:"1";s:5:"Extra";s:0:"";}i:13;a:6:{s:5:"Field";s:6:"Status";s:4:"Type";s:7:"int(11)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";s:1:"1";s:5:"Extra";s:0:"";}}s:23:"ChildInterestDetailList";a:4:{i:0;a:6:{s:5:"Field";s:21:"ChildInterestDetailId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:23:"ChildInterestDetailName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:24:"ChildInterestDetailOrder";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:3;a:6:{s:5:"Field";s:15:"ChildInterestId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:17:"ChildInterestInfo";a:4:{i:0;a:6:{s:5:"Field";s:19:"ChildInterestInfoId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:14:"auto_increment";}i:1;a:6:{s:5:"Field";s:6:"UserId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:15:"ChildInterestId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:3;a:6:{s:5:"Field";s:21:"ChildInterestDetailId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:17:"ChildInterestList";a:3:{i:0;a:6:{s:5:"Field";s:15:"ChildInterestId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:17:"ChildInterestName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:18:"ChildInterestOrder";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:8:"CityList";a:4:{i:0;a:6:{s:5:"Field";s:6:"CityId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:10:"ProvinceId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:8:"CityName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:3;a:6:{s:5:"Field";s:16:"CityOfficialName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:8:"CoinInfo";a:6:{i:0;a:6:{s:5:"Field";s:6:"UserId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:4:"Coin";s:4:"Type";s:10:"bigint(19)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:13:"LastResetDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:3;a:6:{s:5:"Field";s:7:"Version";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:4;a:6:{s:5:"Field";s:10:"CreateDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:5;a:6:{s:5:"Field";s:12:"ModifiedDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:11:"CountryList";a:2:{i:0;a:6:{s:5:"Field";s:9:"CountryId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:11:"CountryName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:13:"EducationList";a:2:{i:0;a:6:{s:5:"Field";s:11:"EducationId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:13:"EducationName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:8:"GeekList";a:2:{i:0;a:6:{s:5:"Field";s:6:"GeekId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:14:"auto_increment";}i:1;a:6:{s:5:"Field";s:4:"Name";s:4:"Type";s:11:"varchar(32)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:9:"GradeList";a:4:{i:0;a:6:{s:5:"Field";s:7:"GradeId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:10:"HonourName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:7:"MinMark";s:4:"Type";s:10:"bigint(19)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:3;a:6:{s:5:"Field";s:7:"MaxMark";s:4:"Type";s:10:"bigint(19)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:10:"IncomeList";a:2:{i:0;a:6:{s:5:"Field";s:8:"IncomeId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:14:"auto_increment";}i:1;a:6:{s:5:"Field";s:10:"IncomeName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:9:"LoginInfo";a:9:{i:0;a:6:{s:5:"Field";s:6:"UserId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:14:"auto_increment";}i:1;a:6:{s:5:"Field";s:8:"Password";s:4:"Type";s:12:"varchar(128)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:11:"LastLoginIp";s:4:"Type";s:12:"varchar(128)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:3;a:6:{s:5:"Field";s:10:"LoginCount";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:4;a:6:{s:5:"Field";s:13:"ResetPwdToken";s:4:"Type";s:8:"char(50)";s:4:"Null";s:3:"YES";s:3:"Key";s:3:"MUL";s:7:"Default";N;s:5:"Extra";s:0:"";}i:5;a:6:{s:5:"Field";s:14:"TokenValidDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:3:"MUL";s:7:"Default";N;s:5:"Extra";s:0:"";}i:6;a:6:{s:5:"Field";s:5:"RegIp";s:4:"Type";s:12:"varchar(128)";s:4:"Null";s:3:"YES";s:3:"Key";s:3:"MUL";s:7:"Default";N;s:5:"Extra";s:0:"";}i:7;a:6:{s:5:"Field";s:7:"RegDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:3:"MUL";s:7:"Default";N;s:5:"Extra";s:0:"";}i:8;a:6:{s:5:"Field";s:12:"ModifiedDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:8:"MarkInfo";a:7:{i:0;a:6:{s:5:"Field";s:6:"UserId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:9:"TotalMark";s:4:"Type";s:10:"bigint(19)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:10:"UsableMark";s:4:"Type";s:10:"bigint(19)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:3;a:6:{s:5:"Field";s:9:"VitalMark";s:4:"Type";s:10:"bigint(19)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:4;a:6:{s:5:"Field";s:9:"CharmMark";s:4:"Type";s:10:"bigint(19)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:5;a:6:{s:5:"Field";s:10:"TalentMark";s:4:"Type";s:10:"bigint(19)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:6;a:6:{s:5:"Field";s:13:"KnowledgeMark";s:4:"Type";s:10:"bigint(19)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:18:"NoDefaultChildInfo";a:9:{i:0;a:6:{s:5:"Field";s:20:"NoDefaultChildInfoId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:6:"UserId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:4:"Name";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:3;a:6:{s:5:"Field";s:8:"NickName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:4;a:6:{s:5:"Field";s:6:"Gender";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:5;a:6:{s:5:"Field";s:9:"BloodType";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:6;a:6:{s:5:"Field";s:9:"BirthDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:7;a:6:{s:5:"Field";s:7:"AddDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:8;a:6:{s:5:"Field";s:12:"ModifiedDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:21:"NoDefaultChildInfoNew";a:15:{i:0;a:6:{s:5:"Field";s:4:"PKId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:14:"auto_increment";}i:1;a:6:{s:5:"Field";s:6:"UserId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"MUL";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:4:"Name";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:3;a:6:{s:5:"Field";s:8:"NickName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:4;a:6:{s:5:"Field";s:9:"BloodType";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:5;a:6:{s:5:"Field";s:6:"Gender";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:6;a:6:{s:5:"Field";s:14:"BirthCountryId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:7;a:6:{s:5:"Field";s:15:"BirthProvinceId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:8;a:6:{s:5:"Field";s:11:"BirthCityId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:9;a:6:{s:5:"Field";s:14:"BirthBoroughId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:10;a:6:{s:5:"Field";s:9:"BirthDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:11;a:6:{s:5:"Field";s:21:"BirthdayModifiedCount";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:12;a:6:{s:5:"Field";s:12:"ModifiedDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:13;a:6:{s:5:"Field";s:14:"IsDefaultChild";s:4:"Type";s:7:"int(11)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";s:1:"0";s:5:"Extra";s:0:"";}i:14;a:6:{s:5:"Field";s:6:"Status";s:4:"Type";s:7:"int(11)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";s:1:"1";s:5:"Extra";s:0:"";}}s:14:"ProfessionList";a:2:{i:0;a:6:{s:5:"Field";s:12:"ProfessionId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:14:"ProfessionName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:12:"ProvinceList";a:4:{i:0;a:6:{s:5:"Field";s:10:"ProvinceId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:9:"CountryId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:12:"ProvinceName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:3;a:6:{s:5:"Field";s:20:"ProvinceOfficialName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:9:"TradeList";a:2:{i:0;a:6:{s:5:"Field";s:7:"TradeId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:9:"TradeName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:12:"UserBaseInfo";a:18:{i:0;a:6:{s:5:"Field";s:6:"UserId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:9:"UserState";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:8:"UserName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:3:"MUL";s:7:"Default";N;s:5:"Extra";s:0:"";}i:3;a:6:{s:5:"Field";s:4:"Name";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:4;a:6:{s:5:"Field";s:8:"NickName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:3:"MUL";s:7:"Default";N;s:5:"Extra";s:0:"";}i:5;a:6:{s:5:"Field";s:9:"AvatarUrl";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:3:"MUL";s:7:"Default";N;s:5:"Extra";s:0:"";}i:6;a:6:{s:5:"Field";s:10:"ResourceId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:7;a:6:{s:5:"Field";s:11:"AvatarState";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:3:"MUL";s:7:"Default";N;s:5:"Extra";s:0:"";}i:8;a:6:{s:5:"Field";s:6:"Gender";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:9;a:6:{s:5:"Field";s:17:"LocationCountryId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:10;a:6:{s:5:"Field";s:18:"LocationProvinceId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:11;a:6:{s:5:"Field";s:14:"LocationCityId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"MUL";s:7:"Default";N;s:5:"Extra";s:0:"";}i:12;a:6:{s:5:"Field";s:17:"LocationBoroughId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"MUL";s:7:"Default";N;s:5:"Extra";s:0:"";}i:13;a:6:{s:5:"Field";s:9:"BirthDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:14;a:6:{s:5:"Field";s:13:"LastLoginDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:15;a:6:{s:5:"Field";s:12:"ModifiedDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:16;a:6:{s:5:"Field";s:8:"VerifyId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:3:"MUL";s:7:"Default";N;s:5:"Extra";s:0:"";}i:17;a:6:{s:5:"Field";s:6:"GeekId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:3:"MUL";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:11:"UserExtInfo";a:20:{i:0;a:6:{s:5:"Field";s:6:"UserId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:9:"AgreeMail";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:5:"Email";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:3:"MUL";s:7:"Default";N;s:5:"Extra";s:0:"";}i:3;a:6:{s:5:"Field";s:13:"EmailActivate";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:4;a:6:{s:5:"Field";s:11:"EducationId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:5;a:6:{s:5:"Field";s:7:"TradeId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:6;a:6:{s:5:"Field";s:12:"ProfessionId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:7;a:6:{s:5:"Field";s:8:"IncomeId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:8;a:6:{s:5:"Field";s:5:"RegIp";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:9;a:6:{s:5:"Field";s:12:"IdentityCard";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:10;a:6:{s:5:"Field";s:9:"Telephone";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:11;a:6:{s:5:"Field";s:15:"MobileTelephone";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:3:"MUL";s:7:"Default";N;s:5:"Extra";s:0:"";}i:12;a:6:{s:5:"Field";s:10:"PostalCode";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:13;a:6:{s:5:"Field";s:3:"MSN";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:14;a:6:{s:5:"Field";s:2:"QQ";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:15;a:6:{s:5:"Field";s:6:"TaoBao";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:16;a:6:{s:5:"Field";s:13:"PostalAddress";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:17;a:6:{s:5:"Field";s:12:"ModifiedDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:18;a:6:{s:5:"Field";s:24:"NickNameLastModifiedTime";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:19;a:6:{s:5:"Field";s:21:"MobileTelephoneIsBind";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";s:2:"-1";s:5:"Extra";s:0:"";}}s:14:"UserGeekDetail";a:4:{i:0;a:6:{s:5:"Field";s:6:"UserId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:7:"Summary";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:8:"GeekDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"MUL";s:7:"Default";N;s:5:"Extra";s:0:"";}i:3;a:6:{s:5:"Field";s:12:"ModifiedDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:22:"UserInterestDetailList";a:4:{i:0;a:6:{s:5:"Field";s:20:"UserInterestDetailId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:22:"UserInterestDetailName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:23:"UserInterestDetailOrder";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:3;a:6:{s:5:"Field";s:14:"UserInterestId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:16:"UserInterestInfo";a:4:{i:0;a:6:{s:5:"Field";s:18:"UserInterestInfoId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:14:"auto_increment";}i:1;a:6:{s:5:"Field";s:6:"UserId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:14:"UserInterestId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:3;a:6:{s:5:"Field";s:20:"UserInterestDetailId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:16:"UserInterestList";a:3:{i:0;a:6:{s:5:"Field";s:14:"UserInterestId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:16:"UserInterestName";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:17:"UserInterestOrder";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:13:"UserSignature";a:2:{i:0;a:6:{s:5:"Field";s:6:"UserId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:9:"Signature";s:4:"Type";s:8:"longtext";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:10:"UserSource";a:2:{i:0;a:6:{s:5:"Field";s:6:"UserId";s:4:"Type";s:7:"int(11)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:10:"SourceType";s:4:"Type";s:10:"tinyint(4)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";s:1:"0";s:5:"Extra";s:0:"";}}s:16:"UserVerifyDetail";a:4:{i:0;a:6:{s:5:"Field";s:6:"UserId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:3:"Why";s:4:"Type";s:12:"varchar(255)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:10:"VerifyDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"MUL";s:7:"Default";N;s:5:"Extra";s:0:"";}i:3;a:6:{s:5:"Field";s:12:"ModifiedDate";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:10:"VerifyList";a:2:{i:0;a:6:{s:5:"Field";s:8:"VerifyId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:14:"auto_increment";}i:1;a:6:{s:5:"Field";s:4:"Name";s:4:"Type";s:11:"varchar(32)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:21:"iur_child_birth_based";a:4:{i:0;a:6:{s:5:"Field";s:6:"UserId";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:6:"Avatar";s:4:"Type";s:10:"tinyint(4)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:13:"ChildBirthday";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:3;a:6:{s:5:"Field";s:11:"LastLoginAt";s:4:"Type";s:8:"datetime";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:11:"sysdiagrams";a:5:{i:0;a:6:{s:5:"Field";s:4:"name";s:4:"Type";s:12:"varchar(128)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:1;a:6:{s:5:"Field";s:12:"principal_id";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"MUL";s:7:"Default";N;s:5:"Extra";s:0:"";}i:2;a:6:{s:5:"Field";s:10:"diagram_id";s:4:"Type";s:7:"int(10)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:14:"auto_increment";}i:3;a:6:{s:5:"Field";s:7:"version";s:4:"Type";s:7:"int(10)";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}i:4;a:6:{s:5:"Field";s:10:"definition";s:4:"Type";s:8:"longblob";s:4:"Null";s:3:"YES";s:3:"Key";s:0:"";s:7:"Default";N;s:5:"Extra";s:0:"";}}s:15:"word_filter_reg";a:2:{i:0;a:6:{s:5:"Field";s:2:"id";s:4:"Type";s:7:"int(11)";s:4:"Null";s:2:"NO";s:3:"Key";s:3:"PRI";s:7:"Default";N;s:5:"Extra";s:14:"auto_increment";}i:1;a:6:{s:5:"Field";s:2:"wk";s:4:"Type";s:12:"varchar(128)";s:4:"Null";s:2:"NO";s:3:"Key";s:0:"";s:7:"Default";s:0:"";s:5:"Extra";s:0:"";}}}
EOT;
	
	private static $tableDesc=null;				//1.service表结构arry形式 由tableDescSerialize 动态生成
	private static $tablePrimaryKey=null;		//1.service所有表的主键信息 由tableDescSerialize 动态生成
	private static $tableLimit=null;			//1.service所有表 由tableDescSerialize 动态生成
	
	
	/*
数据表名称	功能说明
BoroughList	区县列表
CityList	城市列表
ProvinceList	省份列表
CountryList	国家列表
ChildInfo	孩子信息
ChildInterestDetailList	孩子兴趣具体类别列表
ChildInterestInfo	孩子兴趣表
ChildInterestList	孩子兴趣大类列表
CoinInfo	摇豆信息
EducationList	学历列表
GeekList	达人列表
GradeList	等级列表
IncomeList	收入列表
iur_child_birth_based	推荐用户
LoginInfo	登录信息
MarkInfo	积分信息
NoDefaultChildInfo	非默认孩子信息，改版时原宝宝小家有多个孩子，导入到SQLServer时默认孩子之外的孩子信息存放在这里
ProfessionList	职业列表
TradeList	行业列表
UserBaseInfo	用户基本信息
UserExtInfo	用户扩展信息
UserGeekDetail	达人身份细节
UserInterestDetailList	用户兴趣具体类列表
UserInterestInfo	用户兴趣
UserInterestList	用户兴趣大类列表
UserSignature	用户签名档
UserVerifyDetail	用户认证细节
VerifyList	认证列表
UserSource	用户注册来源表
	*/
	const BoroughList='BoroughList';
	const ChildInfo='ChildInfo';
	const ChildInterestDetailList='ChildInterestDetailList';
	const ChildInterestInfo='ChildInterestInfo';
	const ChildInterestList='ChildInterestList';
	const CityList='CityList';
	const CoinInfo='CoinInfo';
	const CountryList='CountryList';
	const EducationList='EducationList';
	const GeekList='GeekList';
	const GradeList='GradeList';
	const IncomeList='IncomeList';
	const LoginInfo='LoginInfo';
	const MarkInfo='MarkInfo';
	const NoDefaultChildInfo='NoDefaultChildInfo';
	const NoDefaultChildInfoNew='NoDefaultChildInfoNew';
	const ProfessionList='ProfessionList';
	const ProvinceList='ProvinceList';
	const TradeList='TradeList';
	const UserBaseInfo='UserBaseInfo';
	const UserExtInfo='UserExtInfo';
	const UserGeekDetail='UserGeekDetail';
	const UserInterestDetailList='UserInterestDetailList';
	const UserInterestInfo='UserInterestInfo';
	const UserInterestList='UserInterestList';
	const UserSignature='UserSignature';
	const UserSource='UserSource';
	const UserVerifyDetail='UserVerifyDetail';
	const VerifyList='VerifyList';
	const iur_child_birth_based='iur_child_birth_based';
	const sysdiagrams='sysdiagrams';
	const word_filter_reg='word_filter_reg';
	
	private static $serviceBaseUrl = 'http://1.service.user.yaolan.com';
    private static $interface = array(		
        'add'   				=> '/Add.aspx',		
        'delete' 				=> '/Delete.aspx',
        'update'     			=> '/Update.aspx',
		'incremental_update'	=>'/IncrementalUpdate.aspx',	
        'get'    				=> '/Get.aspx',
		'dynamic_get'			=>'/DynamicGet.aspx',
    );
	
	/*
		add信息接口
		支持同时add多个表信息，和批量add,注意如果add表的pkid为自增则主键信息不要填写,pkid非自增则必填
		eg:		$data=array(
							'UserBaseInfo'=>array(array('UserId'=>502043411,'NickName'=>'bbbbZdcTest'),array('UserId'=>502043422,'NickName'=>'bbbbZdcTest'),),//批量更新，传一个多维数组
							'ChildInfo'=>array('UserId'=>502043411,'NickName'=>'bbbbZdcTest',),//单个插入可以传一个一维数组
							'NoDefaultChildInfoNew'=>array(array('UserId'=>'50204342','NickName'=>'bbbbZdcTest'),array('UserId'=>'50204342','NickName'=>'bbbbZdcTest'),),
						);
	*/
	public static function addInfo(array $tableDataList){	
		return self::addUpdateCommon('add',$tableDataList);
	}
	
	/*
		update信息接口
		支持同时update多表个信息，和批量update,注意要update表的主键为必填,其他字段更新则填写，不更新则不填 ,更新成功IsSuccess为true，更新失败或每月受影响的行 为false(eg:要更新的值与数据库的值相同)
		eg:		$data=array(
							'UserBaseInfo'=>array(array('UserId'=>502043411111,'NickName'=>'bbbbZdcTest'),array('UserId'=>502043411122,'NickName'=>'bbbbZdcTest'),),//批量更新，传多多维数组
							'ChildInfo'=>array('UserId'=>502043411122,'NickName'=>'bbbbZdcTest',),//单个更新可以传一个一维数组
							'NoDefaultChildInfoNew'=>array(array('PKId'=>502043411111,'NickName'=>'bbbbZdcTest'),array('PKId'=>502043411122,'NickName'=>'bbbbZdcTest'),),
						);
	*/
	public static function updateInfo(array $tableDataList){
		return self::addUpdateCommon('update',$tableDataList);
	}
	
	/*
	增量更新，只限于MarkInfo、CoinInfo两张表(如需增加，请修改1.serevice)	且表的主键为UserId
	eg:	$data=array(
				'MarkInfo'=>array(array('UserId'=>50204341,'TotalMark'=>1),array('UserId'=>50204341,'TotalMark'=>'2'),),
				'CoinInfo'=>array(array('UserId'=>50204341,'Coin'=>'1',)),
			 );
	*/
	public static function incUpdateInfo(array $tableDataList){
		return self::addUpdateCommon('incremental_update',$tableDataList);
	}
	
	//add update common code
	private static function addUpdateCommon($type,array $tableDataList){
			self::initStatic();
			self::filterTableDataList($tableDataList);
			$res=self::curlPost(self::getInterface($type),self::getPostXmlParam($tableDataList));
			return self::convertResultXmlToArray($res,false);
	}
	
	/*
		删除信息接口 idList tableList 可以传字符串 或数组 
		eg:
			$temp= UserProxy::deleteInfo('50204341111','UserBaseInfo');
			$temp= UserProxy::deleteInfo(50204341111,'UserBaseInfo');	
			$temp= UserProxy::deleteInfo('50204341111,50204342111,50204342111',',UserBaseInfo,UserExtInfo,ChildInfo');
			$temp= UserProxy::deleteInfo(array(50204341111,50204342111),array('UserBaseInfo'));
			$temp= UserProxy::deleteInfo(array(50204341111,50204342111),array('UserBaseInfo','UserExtInfo'));
	*/
	public static function deleteInfo($idList,$tableList){
		self::initStatic();
		self::filterIdTable($tableList,$idList);	
		$res=self::curlPost(self::getInterface('delete'),self::getPostXmlParam(self::assemblePostData($idList,$tableList)));
		return self::convertResultXmlToArray($res,false);
	}
	
	/*
		getFirst 是否去结果中的第一个 默认false
		获取信息接口 idList tableList 可以传字符串 或数组 
		eg:			
			$temp= UserProxy::getInfo(array(50204341,50204342),array(UserProxy::UserBaseInfo));//建议这样用 不让表名侵入自己的程序
			$temp= UserProxy::getInfo(array(50204341,50204342),array(UserProxy::UserBaseInfo,UserProxy::UserExtInfo));//建议这样用 不让表名侵入自己的程序
			$temp= UserProxy::getInfo('50204341','UserBaseInfo');
			$temp= UserProxy::getInfo(50204341,'UserBaseInfo');	
			$temp= UserProxy::getInfo('50204341,50204342,50204342',',UserBaseInfo,UserExtInfo,ChildInfo');
	*/
	public static function getInfo($idList,$tableList,$getFirst=false){
		self::initStatic();
		self::filterIdTable($tableList,$idList);		
		$res=self::curlPost(self::getInterface('get'),self::getPostXmlParam(self::assemblePostData($idList,$tableList)));
		$res=self::convertResultXmlToArray($res);
		$getFirst and $res=current(current($res));		
		return $res;
	}

    /*
     * 获取格式化(已UserId为键值，用户信息为val的array)后的用户信息，只能获取，主键为UserId表的信息,没有getInfo通用，但能满足大部分获取用户信息需求而且不用再格式化
     * eg:
     *      $res=UserProxy::getInfoFormat(array(50204341,50204342,50204341),array(UserProxy::UserBaseInfo,UserProxy::ChildInfo,UserProxy::UserExtInfo));
     *      $res=UserProxy::getInfoFormat('50204341,50204342,50204341',array(UserProxy::UserBaseInfo,UserProxy::ChildInfo,UserProxy::UserExtInfo));
     *      $res=UserProxy::getInfoFormat('50204341,50204342,50204341','UserBaseInfo,ChildInfo');
     */
    public static function getInfoFormat($idList,$tableList){
    	$res = [];
        self::initStatic();
        self::filterIdTable($tableList,$idList);
        $userInfo=self::getInfo($idList,$tableList);
        foreach($idList as $userIdItem){
            foreach($tableList as $tableItem){
                foreach($userInfo[$tableItem] as $userInfoItem){
                    if($userIdItem==$userInfoItem['UserId']){
                        $res[$userIdItem][$tableItem]=$userInfoItem;
                        break;
                    }
                }
            }
        }
        return $res;
    }
	
//动态查询
/*
		所有动态查询的sql 均有1.service 管理员同一分发并备案，以防随意查询用户服务,如需查询新的sql请联系 1.service管理员
		$var 格式如下：
		$var=array(
				'Sql'=>'SELECT UserId AS Id FROM UserBaseInfo Where UserName=@UserName AND UserState>0',
				'Sign'=>'cf4b86f808ee64b5adab66b7b3129eb7',
				'Params'=>array(array('name'=>'@UserName','dbType'=>'varchar','value'=>'firstman'),),//多个参数可以再加
			);
		
		$var=array(
			'Sql'=>'SELECT UserId as Id FROM LoginInfo ORDER BY UserId DESC  LIMIT 1',
			'Sign'=>'e94b8b342ccbdf80be66866dc297aae6',
			//'Params'=>array(array('name'=>'@UserName','dbType'=>'varchar','value'=>'firstman'),),//没有参数可以Params 可以不填，或者设置为空数组
		);
*/
	public static function getInfoBySql(array $var,$tableList){
		self::initStatic();
		self::filterIdTable($tableList);
		$res=self::curlPost(self::getInterface('dynamic_get'),self::getDynamicGetPostXmlParam($var,$tableList));
		return self::convertResultXmlToArray($res);
	}

	/////////////// 积分商城首页-总排行2017-04-12 ///////////////
	public static function getInfoBySql2(array $var,$tableList){
		self::initStatic();
		self::filterIdTable($tableList);
		$res = self::curlPost(self::getInterface('dynamic_get'), self::getCoinInfoCountXml($var,$tableList));
		return self::convertResultXmlToArray($res);
//		$res = self::curlPost(self::getInterface('dynamic_get'), self::getCoinInfoCountXml_old());
//echo "<pre>";
//		var_dump($res);exit;
	}

	/**
	 * 获取排名
	 * @author
	 * @param $num 读取数量
	 */
	public static function getCoinInfoCountXml($var,$tableList)
	{

		$res['CoinInfos'] = '<?xml version="1.0" encoding="utf-8"?><CoinInfos><Id ref="CoinInfo" /></CoinInfos>';
		$res['Variables'] = <<<EOT
<?xml version="1.0" encoding="utf-8"?>
<Variables xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <Var id="CoinInfo">
    <Sql>{$var['Sql']}</Sql>
    <Sign>{$var['Sign']}</Sign>
    <Params>
      <Param name="@n" dbType="int">
                    <Value>{$var['Params']['num']}</Value>
               </Param>
               <Param name="@LastResetDate" dbType="varchar">
                    <Value>{$var['Params']['lastResetDate']}</Value>
               </Param>
    </Params>
  </Var>
</Variables>
EOT;

		return http_build_query($res);
	}
	///////////////////////////////////////


	
	//过滤update,add的tableDataList
	private static function filterTableDataList(&$tableDataList){
		foreach($tableDataList as $key=>&$val){
			if(!in_array($key,self::$tableLimit)){
				unset($tableDataList[$key]);	
				continue;
			}
			isset($val[0]) or $val=array($val);
		}
	}
	
	//获取id、table
	private static function filterIdTable(&$tableList,&$idList=null){
		$tableLimit=self::$tableLimit;		
		is_array($idList) or $idList=explode(',',$idList);;
		is_array($tableList) or $tableList=explode(',',$tableList);		
		array_walk($idList,function(&$val){	$val=trim($val);});
		array_walk($tableList,function(&$val){	$val=trim($val);});

		$idList=array_filter($idList,function($v){ return ''!==$v;});
		$tableList=array_filter($tableList,function($v)use($tableLimit){return in_array($v,$tableLimit,true);});
	}
	
	
	//获取1.service curd接口地址
	private static function getInterface($interfaceName){
		return self::$serviceBaseUrl.self::$interface[$interfaceName];
	}
	
	//初始化静态字段
	private static function initStatic(){
		is_null(self::$tableDesc) and self::$tableDesc=unserialize(self::$tableDescSerialize);		
		is_null(self::$tablePrimaryKey) and self::$tablePrimaryKey=self::getTablePrimaryKey();		
		is_null(self::$tableLimit) and self::$tableLimit=array_keys(self::$tableDesc);
	}	
	
	//将get返回的xml字符串 转换为array
	private static function convertResultXmlToArray($xmlStr,$isGet=true){
		$res=array();
		$xml = simplexml_load_string($xmlStr);		
		if(false===$xml){
			return $res;
		}
		
		foreach ($xml->children() as $tableRes) {
			$tableName=substr($tableRes->getName(),0,-1);
			$res[$tableName]=array();
			foreach($tableRes as $actionResult){
				if($isGet){
					'true'==$actionResult->IsSuccess and $res[$tableName][]=self::convertTableXmlToArray($actionResult->Result);				
				}else{
					 $res[$tableName][]=self::convertTableXmlToArray($actionResult->asXml());
				}				
			}
		}
		return $res;
	}
	
	//getInfo时 转换表结构转换为数组
	private static function convertTableXmlToArray($xmlStr){
		$res=array();
		$xml = simplexml_load_string($xmlStr);		
		foreach($xml->children() as $val){
			//$res[$val->getName()]=(string)$val;
			false===strpos($val->asXML(),'xsi:nil="true"')?$res[$val->getName()]=(string)$val:$res[$val->getName()]=null;
		}
		return $res;
	}	
	
	/**                                                                                                                                                           
	 *Author zhudongchang                                                                                                                                         
	 *Date 2015/6/12	原创                                                                                                                                        
	 *xml 转array 函数                                                                                                                                 
	 *@param string $xmlStr xml字符串                                                                                                                   
	 *@return string 
	 */
	public static function convertXmlToArray($xmlStr){
		$patten=<<<EOT
/(^\s*<(\s*[a-zA-Z0-9_='":]+\s*)+>.*<\s*\/(\s*[a-zA-Z0-9_='":]+\s*)+>\s*$)|(^\s*<(\s*[a-zA-Z0-9_='":]+\s*)+\/\s*>$)/s
EOT;
		$pattenChildXml=<<<EOT
/^\s*<(\s*[a-zA-Z0-9_='":]+\s*)+>(.*)<\s*\/(\s*[a-zA-Z0-9_='":]+\s*)+>\s*$/s
EOT;
		$res=array();
		$xml = simplexml_load_string($xmlStr);
		foreach($xml->children() as $val){
			$key=$val->getName();			
			$valXml=$val->asXML();		
			preg_match($pattenChildXml,$valXml,$match);
			$childXmlStr=$match[2];
			//var_dump($match,$valXml,$childXmlStr);die();
			$temp=preg_match($patten,$childXmlStr)?self::convertXmlToArray($valXml):(string)$val;
			if(array_key_exists($key,$res)){
				is_array($res[$key]) or $res[$key]=array($res[$key]);				
				$tempNew=array();
				foreach($res[$key] as $k =>$v){
					if(!is_int($k)){
						$tempNew[]=$v;
						unset($res[$key][$k]);
					}
				}				
				empty($tempNew) or $res[$key][]=$tempNew;
				$res[$key][]=$temp;
			}else{
				$res[$key]=$temp;
			}
		}
		return $res;
	}
	
	//组装postData
	private static function assemblePostData(array $idList,array $tableList){
		$data=array();
		foreach($tableList as $val){			
			reset(self::$tablePrimaryKey[$val]);
			$pkid=current(self::$tablePrimaryKey[$val]);
			$data[$val]=array();
			foreach($idList as $v){
				$data[$val][]=array($pkid=>$v);
			}
		}
		return $data;
	}	

	//获取动态查询 postXML
	private static function getDynamicGetPostXmlParam($var,$tableList){
		$varPostXml=<<<EOT
<?xml version="1.0" encoding="utf-8"?>
<Variables xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <Var id="UserId">
    <Sql>{$var['Sql']}</Sql>
    <Sign>{$var['Sign']}</Sign>
    <Params>
      %s
    </Params>
  </Var>
</Variables>
EOT;
		$tablePostXml=<<<EOT
<?xml version="1.0" encoding="utf-8"?>
<%s xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
  <Id ref="UserId" />
</%s>
EOT;
		$parmStr='';
		if(array_key_exists('Params',$var)&&is_array($var['Params'])){
			foreach($var['Params'] as $val){
				$parmStr.=sprintf('<Param name="%s" dbType="%s" size="256"><Value>%s</Value></Param>',$val['name'],strtolower($val['dbType']),$val['value']);
			}
		}

		$res['Variables']=sprintf($varPostXml,$parmStr);
		foreach($tableList as $key=> $val){
			$res[$val.'s']=sprintf($tablePostXml,$val,$val);
		}
//		var_dump($tableList,$res);die();
		return http_build_query($res);
		
		
	}
	
	//获取1.service post参数
	private static function getPostXmlParam(array $tableDataList){
		$res=array();
		foreach($tableDataList as $key=> $val){
			$res[$key.'s']=self::getTablePostXmlParam($key,$val);
		}
		//var_dump($tableDataList,$res);die();
		return http_build_query($res);
	}
	
	//获取1.service 单表post参数
	private static function getTablePostXmlParam($tableName,$tableData){	
		$postXml=<<<EOT
<?xml version="1.0" encoding="utf-8"?>
<{$tableName}s>
	%s
</{$tableName}s>
EOT;
		$pkidList='';
		foreach($tableData as $val){
			$temp='';
			foreach($val as $k=>$v){
				$temp.=is_null($v)?"<{$k} xsi:nil=\"true\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" />":"<{$k}>{$v}</{$k}>";
			}
			$pkidList.="<{$tableName}>{$temp}</{$tableName}>";
		}
		return sprintf($postXml,$pkidList);
	}
	
	//由tableDescSerialize 获取主键信息
	private static function getTablePrimaryKey(){
		$tablePrimaryKey=array();
		foreach(self::$tableDesc as $tableName=>$itemTableDesc){
				$pks=array();
				foreach($itemTableDesc as $column){
					'PRI' == $column['Key'] and $pks[] = $column['Field'];
				}
				$tablePrimaryKey[$tableName]=$pks;
		}
		return $tablePrimaryKey;
	}
	
	//获取1.service表结构的序列化信息  如果1.service表结构有改变 执行改方法 奖返回值更新到tableDescSerialize 中
	public static function getTableDescSerialize(){
		$tableDesc=array();
		foreach(\Zdc\Db\PDOHelper::query('show tables;') as $val){
			$tableName=array_pop($val);
			$tableDesc[$tableName]=\Zdc\Db\PDOHelper::query("desc $tableName;");
		}		
		echo serialize($tableDesc);
	}

	//curl post
	private static function  curlPost($url,$data=array(),$header=array()){
			$ch=curl_init();
			curl_setopt($ch,CURLOPT_URL,$url);
			curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
			curl_setopt($ch,CURLOPT_POST,true);//如果有下面的一行代码，这个可以不设置
			curl_setopt($ch,CURLOPT_POSTFIELDS,$data);	
			//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);			
			empty($header) or curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			
			$content=curl_exec($ch);
			curl_close($ch);
			return $content;
	}	
}