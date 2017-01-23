# Contact Form 7 Rate Limiting

Adds simple rate limiting to the popular [Contact Form 7](http://contactform7.com/) plugin for WordPress.

## Limitations

- The rate limiting algorithm is very simplistic: it checks to see if the IP address has made 5 or more requests in the past 5 minutes. If it has, the current request is blocked
- No settings page: the 5 minutes and the 5 requests are both hard-coded
- No IPv6 support: an attacker has a bare minimum of 2<sup>64</sup> source addresses at their disposal, probably more. This plugin should track subnets not addresses for IPv6
