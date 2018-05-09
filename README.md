# Json 2 swg

根据提供的Json rpc 的响应字符串生成```Swagger```的描述.需要PHP运行环境支持.

## 使用

```sh 
./start.sh
```

或者

```sh
php -S 127.0.0.1:7856 -t ./ swg.php
```

## 源

```js
{
	"code": 200,
	"message": "Success",
	"data": {
		"id": 1245,
		"name": "Vincent",
		"posts": [
			{
				"postId": 145,
				"title": "New Homepage"
			},
			{
				"postId": 156,  
				"title": "New Post 2"
			}
		]
	}
}
```

## 处理结果

```php
* @SWG\Response(
*     response="200",
*     description="接口响应",
*     @SWG\Schema(
*        @SWG\Property( property="code" , type="integer" , example="200",description="code 描述"),
*        @SWG\Property( property="message" , type="string" , example="Success",description="message 描述"),
*        @SWG\Property( property="data" ,type="object",
*            @SWG\Property( property="id" , type="integer" , example="1245",description="id 描述"),
*            @SWG\Property( property="name" , type="string" , example="Vincent",description="name 描述"),
*            @SWG\Property( property="posts" ,type="array",
*            @SWG\Items(type="object",
*                @SWG\Property( property="postId" , type="integer" , example="145",description="postId 描述"),
*                @SWG\Property( property="title" , type="string" , example="New Homepage",description="title 描述"),*            )
*            ),
*        ),
*     )
* )
```