<?php

/**
 * This file is part of Dimtrovich/Validation.
 *
 * (c) 2023 Dimitri Sitchet Tomkeu <devcode.dst@gmail.com>
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Dimtrovich\Validation;

use BlitzPHP\Utilities\Helpers;
use Dimtrovich\Validation\Exceptions\ValidationException;
use Dimtrovich\Validation\Rules\AbstractRule;
use InvalidArgumentException;
use Rakit\Validation\Rule;
use Rakit\Validation\RuleNotFoundException;
use Rakit\Validation\Validation as RakitValidation;
use Rakit\Validation\Validator as RakitValidator;
use RuntimeException;

class Validation
{
    protected RakitValidator $validator;
    private ?RakitValidation $validation = null;

    /**
     * The exception to throw on failure.
     */
    protected string $exception = ValidationException::class;

    /**
     * Validation messages
     *
     * @var array<string, string>
     */
    protected array $messages = [];

    /**
     * Translations on validation rules
     *
     * @var array<string, string>
     */
    protected array $translations = [];

    /**
     * Alias of keys of datas to validate
     *
     * @var array<string, string>
     */
    protected array $aliases = [];

    /**
     * Data to validate
     */
    private array $data = [];

    /**
     * validation rules
     *
     * @var array<string, mixed>
     */
    protected array $rules = [];

    /**
     * self instance for singleton
     */
    protected static ?self $_instance = null;

    /**
     * Constructor
     */
    public function __construct(protected string $locale = 'en')
    {
        $this->validator = new RakitValidator($this->translations);
        $this->validator->allowRuleOverride(true);
        $this->registerRules([
            Rules\AcceptedIf::class,
            Rules\ActiveURL::class,
            Rules\After::class,
            Rules\AfterOrEqual::class,
            Rules\Alpha::class,
            Rules\AlphaDash::class,
            Rules\AlphaNum::class,
            Rules\Ascii::class,
            Rules\Base64::class,
            Rules\Before::class,
            Rules\BeforeOrEqual::class,
            Rules\BitcoinAddress::class,
            Rules\Camelcase::class,
            Rules\CapitalCharWithNumber::class,
            Rules\CarNumber::class,
            Rules\Confirmed::class,
            Rules\Contains::class,
            Rules\ContainsAll::class,
            Rules\Date::class,
            Rules\DateEquals::class,
            Rules\Decimal::class,
            Rules\Declined::class,
            Rules\DeclinedIf::class,
            Rules\DiscordUsername::class,
            Rules\DoesntEndWith::class,
            Rules\DoesntStartWith::class,
            Rules\Domain::class,
            Rules\Duplicate::class,
            Rules\DuplicateCharacter::class,
            Rules\EndWith::class,
            Rules\Enum::class,
            Rules\EvenNumber::class,
            Rules\Gt::class,
            Rules\Gte::class,
            Rules\Hashtag::class,
            Rules\Hexcolor::class,
            Rules\Htmltag::class,
            Rules\Iban::class,
            Rules\Image::class,
            Rules\Imei::class,
            Rules\InArray::class,
            Rules\Jwt::class,
            Rules\Kebabcase::class,
            Rules\Lt::class,
            Rules\Lte::class,
            Rules\MacAddress::class,
            Rules\NotInArray::class,
            Rules\NotRegex::class,
            Rules\OddNumber::class,
            Rules\Pascalcase::class,
            Rules\Password::class,
            Rules\Pattern::class,
            Rules\Phone::class,
            Rules\Port::class,
            Rules\Prohibited::class,
            Rules\Size::class,
            Rules\SlashEndOfString::class,
            Rules\Slug::class,
            Rules\Snakecase::class,
            Rules\StartWith::class,
            Rules\Timezone::class,
            Rules\TypeArray::class,
            Rules\TypeInstanceOf::class,
            Rules\TypeString::class,
            Rules\Ulid::class,
            Rules\Username::class,
            Rules\Uuid::class,
            Rules\Vatid::class,
        ]);
    }

    /**
     * singleton constructor
     *
     * @return self
     */
    public static function instance()
    {
        if (static::$_instance === null) {
            static::$_instance = new static();
        }

        return static::$_instance;
    }

    /**
     * Get the actual validator instance
     *
     * if parameter $rule passed, return instance of $rule validator
     *
     * @return RakitValidator|Rule
     */
    public function getValidator(?string $rule = null)
    {
        if (null === $rule) {
            return $this->validator;
        }

        return $this->validator->getValidator($rule);
    }

    /**
     * Set the actual validation instance
     */
    public function setValidation(?RakitValidation $validation): self
    {
        $this->validation = $validation;

        return $this;
    }

    /**
     * Add rule validator
     */
    public function addValidator(string $name, AbstractRule $rule): self
    {
        $this->validator->addValidator($name, $rule->locale($this->locale));

        return $this;
    }

    /**
     * Check if validation fails
     */
    public function fails(): bool
    {
        return ! $this->passes();
    }

    /**
     * Check if validation passes
     */
    public function passes(): bool
    {
        $this->validation = $this->validator->make($this->data, $this->rules, $this->messages);
        $this->validation->setMessages($this->translations);
        $this->validation->setTranslations($this->translations);
        $this->validation->setAliases($this->aliases);
        $this->validation->validate();

        return $this->validation->passes();
    }

    /**
     * Run the validator's rules against its data.
     *
     * @throws ValidationException
     */
    public function validate(): array
    {
        $this->throwIf($this->fails());

        return $this->validated();
    }

    /**
     * Run the validator's rules against its data.
     *
     * @throws ValidationException
     */
    public function validateWithBag(string $errorBag): array
    {
        try {
            return $this->validate();
        } catch (ValidationException $e) {
            // $e->errorBag = $errorBag;

            throw $e;
        }
    }

    /**
     * Returns the data which was valid.
     */
    public function valid(): array
    {
        if (! $this->validation) {
            return [];
        }

        return $this->validation->getValidData();
    }

    /**
     * Get the attributes and values that have been validated.
     *
     * @throws ValidationException
     */
    public function validated(): array
    {
        $this->passes();

        $this->throwIf($this->invalid());

        return $this->validation->getValidatedData();
    }

    /**
     * Returns data that was invalid.
     */
    public function invalid(): array
    {
        if (! $this->validation) {
            return [];
        }

        return $this->validation->getInvalidData();
    }

    /**
     * Get a validated input container for the validated input.
     *
     * @return array|ValidatedInput
     */
    public function safe(?array $keys = null)
    {
        $input = new ValidatedInput($this->validated());

        return is_array($keys) ? $input->only($keys) : $input;
    }

    /**
     * Recover errors that occurred during validation
     */
    public function errors(): ErrorBag
    {
        if (! $this->validation) {
            throw new RuntimeException();
        }

        return ErrorBag::fromBase($this->validation->errors());
    }

    /**
     * Definition of the aliases of the data to be validated
     */
    public function alias(array|string $key, string $value = ''): self
    {
        if (is_array($key)) {
            $this->aliases = array_merge($this->aliases, $key);
        } elseif ('' === $value) {
            throw new InvalidArgumentException('Valeur non valide fournie');
        } else {
            $this->aliases[$key] = $value;
        }

        return $this;
    }

    /**
     * Definition of the data to be validated
     */
    public function data(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Definition of locale of error messages
     */
    public function locale(string $locale): self
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * Definition of validation rules
     */
    public function rules(array $rules): self
    {
        $this->rules = array_merge($this->rules, $rules);

        return $this;
    }

    /**
     * Definition of a validation rule
     */
    public function rule(string $key, mixed $rule): self
    {
        return $this->rules([$key => $rule]);
    }

    /**
     * Definition of messages associated with each validation rule
     */
    public function messages(array $messages): self
    {
        $this->messages = array_merge($this->messages, $messages);

        return $this;
    }

    /**
     * Definition of a message associated with a validation rule
     */
    public function message(string $key, string $message): self
    {
        return $this->messages([$key => $message]);
    }

    /**
     * Definition of rule translations
     */
    public function translations(array $translations): self
    {
        $this->translations = array_merge($this->translations, $translations);

        return $this;
    }

    /**
     * Definition of the translation of a rule
     */
    public function translation(string $key, string $value): self
    {
        if ('' === $value) {
            throw new InvalidArgumentException('Invalid value provided');
        }

        return $this->translations([$key => $value]);
    }

    /**
     * Register validation rules
     */
    protected function registerRules(array $rules): void
    {
        foreach ($rules as $key => $value) {
            if (is_int($key)) {
                $name = $value::name();
                $rule = $value;
            } else {
                $name = $value;
                $rule = $key;
            }

            $this->addValidator($name, new $rule());
        }
    }

    /**
     * Magic method to create a rule
     *
     * @throws ValidationException
     */
    public function __invoke(string $rule): Rule
    {
        try {
            return $this->validator->__invoke(...func_get_args());
        } catch (RuleNotFoundException $e) {
            throw ValidationException::ruleNotFound($rule);
        }
    }

    /**
     * Lève l'exception donnée si la condition donnée est vraie.
     *
     * @throws Throwable
     */
    private function throwIf(mixed $condition)
    {
        if (is_a($this->exception, ValidationException::class, true)) {
            Helpers::throwIf($condition, $this->exception, '', $this);
        } else {
            Helpers::throwIf($condition, $this->exception, ValidationException::summarize($this));
        }
    }
}
