# Simple ViewModel Object

The Simple VM allows you to simply create a ViewModel object.   
By using the ViewModel object, you can convey appropriate data to the View.  
Enjoy!  

## How to use
Please use as follows

### Basic
```php
use Takemo101\SimpleVM\ViewModel;

/**
 * Create a class that inherits from the ViewModel class
 */
class TestViewModel extends ViewModel
{
    // Set methods and property names that are not reflected in this property
    protected array $__ignores = [
        'd',
    ];

    /**
     * constructor
     *
     * @param string $a
     * @param integer $b
     */
    public function __construct(
        public string $a,
        private int $b,
    ) {
        // public property is reflected in View
    }

    /**
     * public method is reflected in View
     *
     * @return string
     */
    public function c(): string
    {
        return 'C';
    }

    /**
     * @return string
     */
    public function d(): string
    {
        return 'D';
    }

    /**
     * Set the initial value to the __data method
     *
     * @return mixed[]
     */
    public function __data(): array
    {
        return [
            'e' => 'E',
        ];
    }
}

$model = new TestViewModel('A', 2);

var_dump($model->toArray());
// array(3) {
//   'e' =>
//   string(1) "E"
//   'a' =>
//   string(1) "A"
//   'c' =>
//   string(1) "C"
// }
```

### PHP Attribute
You can use the PHP Attribute class.
```php
use Takemo101\SimpleVM\ViewModel;
use Takemo101\SimpleVM\Attribute\{
    Ignore,
    ChangeName,
};

/**
 * Create a class that inherits from the ViewModel class
 */
class TestAttributeViewModel extends ViewModel
{
    /**
     * Not output when Ignore class is set in a property or method
     *
     * @param string $a
     */
    public function __construct(
        #[Ignore]
        public string $a,
    ) {
        // public property is reflected in View
    }

    /**
     * Setting the ChangeName class on a property or method will rename the data
     *
     * @return string
     */
     #[ChangeName('cc')]
    public function c(): string
    {
        return 'C';
    }
}

$model = new TestAttributeViewModel('A');

var_dump($model->toArray());
// array(3) {
//   'cc' =>
//   string(1) "C"
// }
```
