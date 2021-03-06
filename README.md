Taxonomy
--------

[![Build Status](https://scrutinizer-ci.com/g/sergiors/taxonomy/badges/build.png?b=master)](https://scrutinizer-ci.com/g/sergiors/taxonomy/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/sergiors/taxonomy/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/sergiors/taxonomy/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sergiors/taxonomy/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sergiors/taxonomy/?branch=master)

Taxonomy is an easier way implements Value Object and persist them in JSON format. Like Doctrine Embeddables

Motivation
----------
http://www.postgresql.org/docs/9.5/static/datatype-json.html  
https://dev.mysql.com/doc/refman/5.7/en/json.html

Install
-------
```bash
composer require sergiors/taxonomy "dev-master"
```

How to use
----------

```php
use Sergiors\Taxonomy\Configuration\Annotation as Taxonomy;

class User
{
    /**
     * @Taxonomy\Embedded(
     *     class="Phone",
     *     column=@Taxonomy\Column(name="phone_metadata")
     * )
     */
    private $phone;

    public function getPhone()
    {
        return $this->phone;
    }

    public function setPhone(Phone $phone)
    {
        $this->phone = $phone;
    }
}

/**
 * @Taxonomy\Embeddable
 */
class Phone
{
    /**
     * @Taxonomy\Index
     */
    private $number;

    /**
     * @Taxonomy\Index(name="actived")
     */
    private $active;

    public function __construct()
    {
        $this->active = false;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function isActive()
    {
        return $this->active;
    }

    public function setNumber($number)
    {
        $this->number = preg_replace('/\D+/', '', $number);
    }
}
```

License
-------
MIT
