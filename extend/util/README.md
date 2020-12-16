BTCHelper
=========

Btc Helper is a class with some helper methods for bitcoin

### Formatting

You can deal with Satoshis everywhere in your code and only convert to BTC at display time like so:

```php
  use JaycoDesign\BTCHelper\BTCHelper;
  $one_satoshi = BTCHelper::format(1); // 0.00000001
```

### Conversion  
```php
BTCHelper::convertToBTCFromSatoshi(303490); // 0.0030349
BTCHelper::convertToSatoshiFromBTC("0.00000001"); // 1
BTCHelper::convertBTCToMilliBits("0.001"); // 1
BTCHelper::convertMilliBitsToBTC(1); // 0.001
BTCHelper::convertBTCToMicroBits("0.000001"); // 1
BTCHelper::convertMicroBitsToBTC(1); // 0.000001

```

### Bitcoin Address Validation

```php
BTCHelper::validBitcoinAddress("1Af3EHHrbYRwaj4dcbKKcBxYxc6Z8j7xMZ"); // TRUE
BTCHelper::validBitcoinAddress("POO"); // FALSE
```



Decimal
=======

Simple wrapper around BC Math for simple math functions

## Usage Examples

```php
  use JaycoDesign\Decimal\Decimal;
  
  Decimal::mul(10, 5); // 50
  Decimal::div(10, 5); // 2
  Decimal::add(10, 5); // 15
  Decimal::sub(10, 5); // 5
  
  Decimal::greater_than(1,2); // TRUE
  Decimal::less_than(1,2); // FALSE
  
  Decimal::trim(10.3400000); // 10.34
  
  
```


## Hash
> 创建密码的哈希

```
// 创建
Hash::make($value, $type = null, array $options = [])

// 检查
Hash::check($value, $hashedValue, $type = null, array $options = [])

```

## Time
> 时间戳操作

```
// 今日开始和结束的时间戳
Time::today();

// 昨日开始和结束的时间戳
Time::yesterday();

// 本周开始和结束的时间戳
Time::week();

// 上周开始和结束的时间戳
Time::lastWeek();

// 本月开始和结束的时间戳
Time::month();

// 上月开始和结束的时间戳
Time::lastMonth();

// 今年开始和结束的时间戳
Time::year();

// 去年开始和结束的时间戳
Time::lastYear();

// 获取7天前零点到现在的时间戳
Time::dayToNow(7)

// 获取7天前零点到昨日结束的时间戳
Time::dayToNow(7, true)

// 获取7天前的时间戳
Time::daysAgo(7)

//  获取7天后的时间戳
Time::daysAfter(7)

// 天数转换成秒数
Time::daysToSecond(5)

// 周数转换成秒数
Time::weekToSecond(5)

```