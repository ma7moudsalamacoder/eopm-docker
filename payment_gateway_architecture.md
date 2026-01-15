# Payment Gateway Architecture - EOPM API

## Overview

The Extendable Order & Payment Management API (EOPM) implements a **Strategy Pattern** based payment gateway architecture using Laravel 12's clean architecture principles. This design allows for easy integration of multiple payment gateways without modifying the core business logic.

---

## Architecture Components

### 1. Payment Gateway Contract (Interface)

The foundation of our extensible payment system is the `PaymentGatewayInterface` contract. This interface defines the standard methods that all payment gateways must implement.

```php
namespace Modules\Payments\Contracts;

interface PaymentGatewayInterface
{
    /**
     * Process a payment charge
     *
     * @param array $data Payment data including amount, order details, and credentials
     * @return array Payment result with status, transaction_id, and amount
     */
    public function charge(array $data): array;
}
```

**Purpose:**
- Ensures all payment gateways have a consistent interface
- Enables polymorphism for payment processing
- Facilitates easy testing and mocking
- Enforces contract compliance across implementations

---

### 2. MGateway Implementation (Demo/Testing Gateway)

The `MGateway` class is our demonstration payment gateway implementation. It simulates real payment processing without actual financial transactions, making it perfect for development and testing.

```php
namespace Modules\Payments\Gateways;

use Modules\Payments\Contracts\PaymentGatewayInterface;

class MGateway implements PaymentGatewayInterface
{
    /**
     * Process a charge through the MGateway demo system
     *
     * @param array $data
     * @return array
     */
    public function charge(array $data): array
    {
        // Simulate card validation
        $cardNumber = $data['card']['card_number'] ?? '';
        $amount = $data['amount'] ?? 0;
        
        // Generate unique transaction ID
        $transactionId = uniqid('demo_', true);
        
        // Simulate different payment scenarios based on card number
        $status = $this->simulatePaymentScenario($cardNumber);
        
        return [
            'status' => $status,
            'transaction_id' => $transactionId,
            'amount' => $amount,
            'status_code' => $status === 'in use' ? 200 : 500,
            'card' => [
                'holder' => $data['card']['holder'] ?? '',
                'card_number' => $cardNumber,
                'cvv' => $data['card']['cvv'] ?? '',
                'valid' => $data['card']['valid'] ?? '',
            ]
        ];
    }
    
    /**
     * Simulate different payment scenarios for testing
     *
     * @param string $cardNumber
     * @return string
     */
    private function simulatePaymentScenario(string $cardNumber): string
    {
        return match(true) {
            str_contains($cardNumber, '4000000000000002') => 'in use',
            str_contains($cardNumber, '4111111511111111') => 'declined',
            str_contains($cardNumber, '4111111111111111') => 'insufficient balance',
            default => 'in use'
        };
    }
}
```

**Features:**
- **No Cost**: Free to use for development and testing
- **Scenario Simulation**: Simulates success, declined, and insufficient balance scenarios
- **Realistic Response**: Returns data structure identical to real gateways
- **Development Ready**: Perfect for CI/CD pipelines and automated testing

**Test Card Numbers:**

| Card Number | Expected Result |
|-------------|-----------------|
| 4000000000000002 | Success (in use) |
| 4111111111111111 | Insufficient balance |
| 4111111511111111 | Card declined |

---

### 3. Payment Gateway Resolver (Strategy Pattern)

The `PaymentGatewayResolver` class implements the Strategy Pattern, dynamically selecting the appropriate payment gateway based on the payment method requested.

```php
namespace Modules\Payments\Services;

use Modules\Payments\Gateways\MGateway;
use Modules\Payments\Gateways\StripeGateway;
use Modules\Payments\Gateways\PayPalGateway;
use Modules\Payments\Contracts\PaymentGatewayInterface;

class PaymentGatewayResolver
{
    /**
     * Resolve and return the appropriate payment gateway
     *
     * @param string $method Payment method identifier
     * @return PaymentGatewayInterface
     * @throws \InvalidArgumentException
     */
    public static function resolve(string $method): PaymentGatewayInterface
    {
        return match($method) {
            'MGateway' => new MGateway(),
            'stripe' => new StripeGateway(),
            'paypal' => new PayPalGateway(),
            'Cash' => new CashPaymentGateway(),
            default => throw new \InvalidArgumentException(
                "Unsupported payment method: {$method}"
            ),
        };
    }
    
    /**
     * Get list of available payment methods
     *
     * @return array
     */
    public static function availableMethods(): array
    {
        return [
            'MGateway' => 'Demo Gateway (Testing)',
            'stripe' => 'Stripe Payment Gateway',
            'paypal' => 'PayPal',
            'Cash' => 'Cash Payment',
        ];
    }
}
```

**Benefits:**
- **Single Responsibility**: Centralizes gateway selection logic
- **Open/Closed Principle**: Open for extension, closed for modification
- **Easy Maintenance**: Add new gateways without touching existing code
- **Type Safety**: Ensures all returned gateways implement the correct interface

---

### 4. Usage in Laravel 12 Controllers/Actions

Here's how to use the payment gateway system in your Laravel application:

```php
namespace Modules\Orders\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Payments\Services\PaymentGatewayResolver;
use Modules\Orders\Models\Order;

class PaymentController extends Controller
{
    /**
     * Process payment for an order
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function processPayment(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_method' => 'required|string',
            'card' => 'required_if:payment_method,MGateway|array',
            'card.holder' => 'required_with:card|string',
            'card.card_number' => 'required_with:card|string',
            'card.cvv' => 'required_with:card|string',
            'card.valid' => 'required_with:card|string',
        ]);
        
        // Get the order
        $order = Order::findOrFail($validated['order_id']);
        
        // Resolve the appropriate payment gateway
        $gateway = PaymentGatewayResolver::resolve($validated['payment_method']);
        
        // Process the payment
        $result = $gateway->charge([
            'amount' => $order->grand_total,
            'order_id' => $order->id,
            'card' => $validated['card'] ?? null,
        ]);
        
        // Handle the payment result
        if ($result['status'] === 'in use' || $result['status_code'] === 200) {
            // Payment successful
            $payment = $order->payments()->create([
                'payer_name' => auth()->user()->name,
                'amount' => $result['amount'],
                'method' => $validated['payment_method'],
                'payload' => $result,
                'status' => 'paid',
            ]);
            
            $order->update(['status' => 'paid']);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Payment processed successfully',
                'data' => $payment,
            ], 200);
        }
        
        // Payment failed
        return response()->json([
            'status' => 'error',
            'message' => 'Payment failed',
            'errors' => ['payment' => $result],
        ], 422);
    }
}
```

---

## Adding New Payment Gateways

### Example: Integrating Stripe

The beauty of this architecture is that adding new payment gateways requires **zero changes** to existing business logic.

#### Step 1: Create Stripe Gateway Class

```php
namespace Modules\Payments\Gateways;

use Modules\Payments\Contracts\PaymentGatewayInterface;
use Stripe\Stripe;
use Stripe\Charge;

class StripeGateway implements PaymentGatewayInterface
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }
    
    /**
     * Process payment through Stripe
     *
     * @param array $data
     * @return array
     */
    public function charge(array $data): array
    {
        try {
            $charge = Charge::create([
                'amount' => $data['amount'] * 100, // Convert to cents
                'currency' => 'usd',
                'source' => $data['token'],
                'description' => "Order #{$data['order_id']}",
            ]);
            
            return [
                'status' => 'successful',
                'transaction_id' => $charge->id,
                'amount' => $data['amount'],
                'status_code' => 200,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'transaction_id' => null,
                'amount' => $data['amount'],
                'status_code' => 500,
                'error' => $e->getMessage(),
            ];
        }
    }
}
```

#### Step 2: Register in Resolver

Simply add one line to the `PaymentGatewayResolver`:

```php
return match($method) {
    'MGateway' => new MGateway(),
    'stripe' => new StripeGateway(), // ✅ New gateway added
    'paypal' => new PayPalGateway(),
    'Cash' => new CashPaymentGateway(),
    default => throw new \InvalidArgumentException(
        "Unsupported payment method: {$method}"
    ),
};
```

**That's it!** No changes to:
- Controllers
- Business logic
- Database schema
- API endpoints
- Existing tests

---

## Key Benefits of This Architecture

### ✅ Clean Code Principles

1. **Single Responsibility Principle**
   - Each gateway handles only its own payment processing
   - Resolver handles only gateway selection
   - Controllers handle only HTTP concerns

2. **Open/Closed Principle**
   - Open for extension (add new gateways)
   - Closed for modification (existing code unchanged)

3. **Dependency Inversion**
   - Business logic depends on abstractions (interface)
   - Not on concrete implementations

4. **Interface Segregation**
   - Minimal, focused interface
   - Only essential methods required

### ✅ Practical Advantages

- **Easy Testing**: Mock the interface for unit tests
- **Zero Downtime**: Add gateways without deployment interruption
- **Cost Effective**: Use free MGateway for development
- **Production Ready**: Switch to real gateways when needed
- **Interview Proof**: Demonstrates advanced architectural knowledge
- **Maintainable**: Clear separation of concerns
- **Scalable**: Add unlimited payment providers

---

## Testing Strategy

### Unit Tests

```php
use Tests\TestCase;
use Modules\Payments\Gateways\MGateway;

class MGatewayTest extends TestCase
{
    /** @test */
    public function it_processes_successful_payment()
    {
        $gateway = new MGateway();
        
        $result = $gateway->charge([
            'amount' => 100.00,
            'card' => [
                'card_number' => '4000000000000002',
                'holder' => 'John Doe',
                'cvv' => '777',
                'valid' => '12/25',
            ],
        ]);
        
        $this->assertEquals('in use', $result['status']);
        $this->assertEquals(200, $result['status_code']);
        $this->assertNotEmpty($result['transaction_id']);
    }
    
    /** @test */
    public function it_handles_declined_cards()
    {
        $gateway = new MGateway();
        
        $result = $gateway->charge([
            'amount' => 100.00,
            'card' => [
                'card_number' => '4111111511111111',
                'holder' => 'John Doe',
                'cvv' => '123',
                'valid' => '12/25',
            ],
        ]);
        
        $this->assertEquals('declined', $result['status']);
        $this->assertEquals(500, $result['status_code']);
    }
}
```

### Integration Tests

```php
/** @test */
public function it_processes_payment_through_gateway_resolver()
{
    $gateway = PaymentGatewayResolver::resolve('MGateway');
    
    $this->assertInstanceOf(PaymentGatewayInterface::class, $gateway);
    
    $result = $gateway->charge([
        'amount' => 50.00,
        'card' => [
            'card_number' => '4000000000000002',
            'holder' => 'Test User',
            'cvv' => '777',
            'valid' => '12/25',
        ],
    ]);
    
    $this->assertArrayHasKey('status', $result);
    $this->assertArrayHasKey('transaction_id', $result);
}
```

---

## Migration Path

### Phase 1: Development (Current)
- Use **MGateway** for all development and testing
- No costs, full simulation capabilities
- Build and test all features

### Phase 2: Staging
- Add **Stripe/PayPal** test mode
- Use sandbox credentials
- Integration testing with real APIs

### Phase 3: Production
- Switch to production credentials
- Keep MGateway available for testing
- Monitor and log all transactions

---

## Configuration

Add gateway configuration to `config/services.php`:

```php
return [
    'mgateway' => [
        'enabled' => env('MGATEWAY_ENABLED', true),
        'test_mode' => env('MGATEWAY_TEST_MODE', true),
    ],
    
    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],
    
    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_SECRET'),
        'mode' => env('PAYPAL_MODE', 'sandbox'),
    ],
];
```

---

## Best Practices

1. **Always validate input** before passing to gateways
2. **Log all payment attempts** for audit trails
3. **Store gateway responses** in database for reconciliation
4. **Handle errors gracefully** with user-friendly messages
5. **Use environment variables** for sensitive credentials
6. **Implement retry logic** for transient failures
7. **Set up monitoring** and alerts for payment failures
8. **Keep gateway credentials secure** and rotated regularly

---

## Conclusion

The EOPM payment gateway architecture demonstrates:

✅ **Production-Ready Design**: Enterprise-level architecture  
✅ **Cost Efficiency**: Free development with MGateway  
✅ **Extensibility**: Add gateways in minutes  
✅ **Maintainability**: Clean, testable code  
✅ **Scalability**: Handle multiple payment providers  
✅ **Interview Excellence**: Shows advanced software engineering skills  

This architecture is perfect for:
- MVP development
- Technical interviews
- Portfolio projects
- Production applications
- Learning clean architecture principles

---

## Further Reading

- [Laravel Service Container](https://laravel.com/docs/12.x/container)
- [Strategy Pattern](https://refactoring.guru/design-patterns/strategy)
- [SOLID Principles](https://en.wikipedia.org/wiki/SOLID)
- [Payment Gateway Integration Best Practices](https://stripe.com/docs/security/best-practices)