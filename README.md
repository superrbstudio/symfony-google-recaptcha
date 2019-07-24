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
    $builder->add('recaptcha', HiddenType::class, [
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

### Javascript (Compatible with Turbolinks)
Create a constant for the site key and create a container to hold the configuration. This should be put in your layout so that it is always available when using Turbolinks, if you aren't using Turbolinks then it only needs to be on the page with the form you are protecting.
```twig
<script>const RECAPTCHA_KEY = '{{ google_recaptcha_site_key() }}';</script>
<div id="recaptcha-container" data-turbolinks-permanent></div>
```
Use this Javascript component and customise to your needs
```javascript
import LiveNodeList from 'live-node-list'
import {bind} from 'decko'
import { load as loadRecaptcha } from 'recaptcha-v3'

export default class GoogleRecaptcha {
  forms = new LiveNodeList('form.form--contact')

  constructor () {
    this.registerListeners()
  }

  @bind
  handleSubmit (e) {
    const recaptchaInput = document.querySelector('input.superrb-google-recaptcha')
    if (recaptchaInput && !recaptchaInput.value) {
      e.preventDefault()
      e.stopPropagation()
      return this.setupRecaptcha(e)
    }

    return true
  }

  @bind
  async setupRecaptcha(e) {
    const recaptchaInput = document.querySelector('input.superrb-google-recaptcha')
    if (recaptchaInput) {
      // Load recaptcha library
      if (!window.recaptcha) {
        window.recaptcha = await loadRecaptcha('explicit', {autoHideBadge: true})
        this.recaptchaHandle = grecaptcha.render('recaptcha-container', {
          'sitekey': RECAPTCHA_KEY,
          'badge': 'inline', // must be inline
          'size': 'invisible' // must be invisible

        })
      }

      // Get a recaptcha token
      const token = await window.grecaptcha.execute(this.recaptchaHandle)

      // Append the recaptcha token to the form
      recaptchaInput.value = token

      const form = e.currentTarget || e.target
      form.submit()
    }
  }

  @bind
  registerListeners() {
    const recaptchaInput = document.querySelector('input.superrb-google-recaptcha')
    if(recaptchaInput) {
      recaptchaInput.value = null
    }

    this.forms.addEventListener('submit', this.handleSubmit)
  }
}
```
### Ajax form using jQuery

Load the library and create a global constant for the site key:

```twig
{{ google_recaptcha_output_src() | raw }}
<script>const RECAPTCHA_KEY = '{{ google_recaptcha_site_key() }}';</script>
```
Bind a jQuery event to the form submit that generates a token and inserts it into the hiden field
```javascript
$('form').unbind('submit').submit(function(e){
    e.preventDefault();
    var form = $(this);

    // get the token
    grecaptcha.ready(function() {
        grecaptcha.execute(RECAPTCHA_KEY, {action: 'homepage'}).then(function (token) {
            // add the token to the hidden field
            $('input.superrb-google-recaptcha').val(token);
            
            // Process and submit form
        });
    });
});
```

## Issues and Troubleshooting
All issues: tech@superrb.com
