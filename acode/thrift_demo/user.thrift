namespace php UserModule

struct User {
	1:optional i64 id,
	2:required string username,
	3:optional string email,
	4:optional string password,
	5:optional i32	 age,
	6:optional string tel
}
exception InvalidException {
	1:i32 code,
	2:string message
}

enum UserStatus {
	DefaultValue=0, //新创建未认证
	isAuthed=1,     //已认证
	isDeleted=2     //已删除
}

struct InParamsUser {
	1:optional string  username,
	2:optional i32 	   age,
	3:optional string  email,
	4:optional string  tel,
	5:optional i32     status = UserStatus.DefaultValue,
	6:optional i32 	   page,
	7:optional i32     pageSize
}

struct OutputParamsUser {
	1:i32 page,
	2:i32 pageSize,
	3:i32 totalNum,
	4:list<User> records
}

service LoginService {
	User Login(1:string username, 2:string pwd) throws (1:InvalidException ex),
	User Register(1:string username,2:string pwd) throws (1:InvalidException ex),
	string getCheckCode(1:string sessionid) throws (1:InvalidException ex),
	string verifyCheckCode(1:string sessionid, 2:string checkcode) throws (1:InvalidException ex)
}

service UserService {
	User Detail(1:i64 id) throws (1:InvalidException ex),
	User Update(1:User user) throws (1:InvalidException ex),
	OutputParamsUser search(1:InParamsUser inParams),
	map<i32,string> getAllStatus()
}

