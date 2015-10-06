Taxonomy
--------

Install
-------
```bash
composer require sergiors/taxonomy
```

How to use
----------

```php
<?php
use Sergiors\Taxonomy\Configuration\Annotation\Taxonomy;
use Sergiors\Taxonomy\Configuration\Annotation\Taxon;

class User
{
    /**
     * @Taxonomy(column=@Column(name="taxons_column"))
     */
    private $taxonomy;

    /**
     * @Taxon(class="Phone")
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


// phone.php file
class Phone
{
    private $number;

    public function getNumber()
    {
        return $this->number;
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