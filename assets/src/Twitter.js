
var DEBUG = (process.env.DEBUG !== undefined);
var OAuth = require('oauth').OAuth
var qs = require('qs')

var config = {
	twitterConsumerKey:'',
	twitterConsumerSecret:'',
	twitterAccessToken:'',
	twitterAccessTokenSecret:'',
	twitterCallBackUrl:''
}
class Twitter{
	constructor(config){
		try{
			this.consumerKey = config.twitterConsumerKey
			this.consumerSecret = config.twitterConsumerSecret
			this.accessToken = config.twitterAccessToken
			this.accessTokenSecret = config.twitterAccessTokenSecret
			this.callBackUrl = config.twitterCallBackUrl
			this.baseUrl = 'https://api.twitter.com/1.1'
			this.oauth = new OAuth(
				'https://api.twitter.com/oauth/request_token',
                'https://api.twitter.com/oauth/access_token',
                this.consumerKey,
                this.consumerSecret,
				'1.0',
				this.callBackUrl,
				'HMAC-SHA1'
                )
		}catch(err){
			console.log("Error on Twitter constructor")
		}
	}


	methods(){

	}
}