Taxonomy
--------

Install
-------
``bash
composer require sergiors/taxonomy
``

How to use
----------

``php
<?php
class User
{
    /**
     * @Taxonomy(column=@Column(name=""))
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