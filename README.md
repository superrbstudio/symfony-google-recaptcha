# Google Recaptcha
Google Recaptcha v3 Symfony 4 Bundle

## Installation

`composer require superrb/google-recaptcha`

Create the following environment variables:

```bash
###> superrb/google-recaptcha ###
SUPERRB_GOOGLE_RECAPTCHA_SITE_KEY="Get From https://www.google.com/recaptcha/intro/v3.html"
SUPERRB_GOOGLE_RECAPTCHA_SECRET_KEY="Get From https://www.google.com/recaptcha/intro/v3.html"
###< superrb/google-recaptcha ###
```

Add the validator to your form, this will create a hidden field that we must put the returned token in to allow server side verification.

```php
use Superrb\GoogleRecaptchaBundle\Validator\Constraint\GoogleRecaptcha;

// ...

public function buildForm(FormBuilderInterface $builder, array $options)
{
    // ...
    $builder->add'recaptcha', HiddenType::class, [
        'attr' => [
            'class' => 'superrb-google-recaptcha',
        ],
        'constraints' => [
            new GoogleRecaptcha(),
        ],
    ]);
}
```

The class is important as it is used to identify the field on the front end when using the standard integration. If you are creating your own frontend integration then you can identify the field in your own way.

## Front End Integration

### Standard Integration

You can use the following Twig function to output the standard frontend integration. This will automatically generate a token and add it to the hidden field. This is the simplest integration however it does come with some caveats:

- If the form errors the token will now be invalid and can't be used again. You could empty the field in your controller after an error and it will be repopulated.
- The token will expire after 2 minutes. If your form is long it could expire before the form is submitted and the user will fail validation.

```twig
{{ google_recaptcha_standard_integration() | raw }}
{{ form_start(form) }}
{{ form_end(form) }}
```

### Ajax form using jQuery

If you are submitting the form using Ajax and jQuery you can use the following integration:

Load the library and create a global variable for the site key:

```twig
{{ google_recaptcha_output_src() | raw }}
<script>var recaptchaSiteKey = '{{ google_recaptcha_site_key() }}';</script>
```

```javascript
$('form').unbind('submit').submit(function(e){
    e.preventDefault();
    var form = $(this);

    // get the token
    grecaptcha.ready(function() {
        grecaptcha.execute(recaptchaSiteKey, {action: 'homepage'}).then(function (token) {
            // add the token to the hidden field
            $('input.superrb-google-recaptcha').val(token);
            
            // Process and submit form
        });
    });
});
```

## Issues and Troubleshooting
All issues: tech@superrb.com
