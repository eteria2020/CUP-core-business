<?php

namespace BusinessCore\Entity;

use Doctrine\ORM\Mapping as ORM;
use SharengoCore\Utils\Interval;

/**
 * BusinessInvoice
 *
 * @ORM\Table(name="business_invoice", schema="business")
 * @ORM\Entity(repositoryClass="BusinessCore\Entity\Repository\BusinessInvoiceRepository")

 */
class BusinessInvoice
{
    const TYPE_SUBSCRIPTION = 'subscription';
    const TYPE_TRIP = 'trip';
    const TYPE_EXTRA = 'extra';
    const TYPE_TIME_PACKAGE = 'time_package';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="business.business_invoice_id_seq", allocationSize=1, initialValue=1)
     */
    private $id;

    /**
     * @var Business
     * @ORM\ManyToOne(targetEntity="Business")
     * @ORM\JoinColumn(name="business_code", referencedColumnName="code", nullable=false)
     */
    private $business;

    /**
     * @var string
     *
     * @ORM\Column(name="invoice_number", type="string", nullable=false)
     */
    private $invoiceNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="generated_ts", type="datetime", nullable=false)
     */
    private $generatedTs;

    /**
     * @var array
     *
     * @ORM\Column(name="content", type="json_array", nullable=false)
     */
    private $content = [];

    /**
     * @var integer
     *
     * @ORM\Column(name="version", type="integer", nullable=false)
     */
    private $version;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="invoice_date", type="integer", nullable=false)
     */
    private $invoiceDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer", nullable=false)
     */
    private $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="vat", type="integer", nullable=false)
     */
    private $vat;

    /**
     * @var BusinessFleet
     * @ORM\ManyToOne(targetEntity="BusinessFleet")
     * @ORM\JoinColumn(name="fleet_id", referencedColumnName="id", nullable=false)
     */
    private $fleet;

    /**
     * BusinessInvoice constructor.
     * @param Business $business
     * @param string $invoiceNumber
     * @param $version
     * @param $type
     * @param $invoiceDate
     * @param array $amounts
     * @param BusinessFleet $fleet
     */
    public function __construct(
        Business $business,
        $invoiceNumber,
        $version,
        $type,
        $invoiceDate,
        array $amounts,
        BusinessFleet $fleet = null
    ) {
        $this->business = $business;
        $this->invoiceNumber = $invoiceNumber;
        $this->generatedTs = date_create();
        $this->version = $version;
        $this->type = $type;
        $this->invoiceDate = $invoiceDate;
        $this->amount = $amounts['sum']['grand_total_cents'];
        $this->vat = $amounts['vat'];

        if ($fleet instanceof BusinessFleet) {
            $this->fleet = $fleet;
        } else {
            $this->fleet = $business->getFleet();
        }

        $this->content = [
            'invoice_date' => $this->getInvoiceDate(),
            'amounts' => $amounts['sum'],
            'vat' => $amounts['vat'],
            'business' => [
                'name' => $business->getName(),
                'email' => $business->getEmail(),
                'address' => $business->getAddress(),
                'town' => $business->getCity(),
                'province' => $business->getProvince(),
                'zip_code' => $business->getZipCode(),
                'piva' => $business->getVatNumber()
            ],
            'type' => $type,
            'template_version' => $version,
            'header' => $this->fleet->getInvoiceHeader()
        ];
    }

    /**
     * @param Business $business
     * @param $invoiceNumber
     * @param TimePackagePayment[] $packagePayments
     * @param $templateVersion
     * @param array $amounts with fields grand_total_cents, grand_total, total, iva
     * @return BusinessInvoice
     */
    public static function createInvoiceForTimePackage(
        Business $business,
        $invoiceNumber,
        array $packagePayments,
        $templateVersion,
        $amounts
    ) {
        $invoiceDate = date_create();
        $formattedInvoiceNumber = self::formatInvoiceNumber($invoiceDate, $business->getFleet(), $invoiceNumber);
        $invoice = new BusinessInvoice(
            $business,
            $formattedInvoiceNumber,
            $templateVersion,
            self::TYPE_TIME_PACKAGE,
            $invoiceDate->format('Ymd'),
            $amounts
        );
        $total = 0;
        foreach ($packagePayments as $payment) {
            $total += $payment->getAmount();
        }

        $invoice->amount = $total;
        $body = [];

        foreach ($packagePayments as $key => $payment) {
            $body[] = [
                [$payment->getCreatedTs()->format('d-m-Y H:i:s')],
                [$payment->getTimePackage()->getMinutes()],
                [$amounts['rows'][$key] . ' €']
            ];
        }

        $invoice->setContentBody([
            'greeting_message' => '<p>Nella pagina successiva troverà i dettagli del pagamento per i pacchetti da lei acquistati<br>' .
                'L\'importo totale della fattura è di EUR ' .
                $amounts['sum']['grand_total'] .
                '</p>',
            'contents' => [
                'header' => [
                    'Data pagamento',
                    'Totale minuti',
                    'Totale'
                ],
                'body' => $body,
                'body-format' => [
                    'alignment' => [
                        'left',
                        'left',
                        'right'
                    ]
                ]
            ]
        ]);

        return $invoice;
    }

    /**
     * @param Business $business
     * @param $invoiceNumber
     * @param SubscriptionPayment[] $subscriptionPayments
     * @param $templateVersion
     * @param $amounts
     * @return BusinessInvoice
     */
    public static function createInvoiceForSubscription(
        Business $business,
        $invoiceNumber,
        array $subscriptionPayments,
        $templateVersion,
        $amounts
    ) {
        $invoiceDate = date_create();
        $formattedInvoiceNumber = self::formatInvoiceNumber($invoiceDate, $business->getFleet(), $invoiceNumber);
        $invoice = new BusinessInvoice(
            $business,
            $formattedInvoiceNumber,
            $templateVersion,
            self::TYPE_SUBSCRIPTION,
            $invoiceDate->format('Ymd'),
            $amounts
        );
        $total = 0;
        foreach ($subscriptionPayments as $payment) {
            $total += $payment->getAmount();
        }

        $invoice->amount = $total;
        $body = [];

        foreach ($subscriptionPayments as $key => $payment) {
            $body[] = [
                [$payment->getCreatedTs()->format('d-m-Y H:i:s')],
                [$amounts['rows'][$key] . ' €']
            ];
        }

        $invoice->setContentBody([
            'greeting_message' => '<p>Nella pagina successiva troverà i dettagli del pagamento per la sottoscrizione al servizio<br>' .
                'L\'importo totale della fattura è di EUR ' .
                $amounts['sum']['grand_total'] .
                '</p>',
            'contents' => [
                'header' => [
                    'Data pagamento',
                    'Totale'
                ],
                'body' => $body,
                'body-format' => [
                    'alignment' => [
                        'left',
                        'right'
                    ]
                ]
            ]
        ]);

        return $invoice;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * @return \DateTime
     */
    public function getGeneratedTs()
    {
        return $this->generatedTs;
    }

    /**
     * @return array
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getInvoiceDate()
    {
        return $this->invoiceDate;
    }

    /**
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return int
     */
    public function getVat()
    {
        return $this->vat;
    }

    /**
     * @var array $body
     * @return BusinessInvoice
     */
    public function setContentBody($body)
    {
        $this->content['body'] = $body;

        return $this;
    }

    /**
     * @param \DateTime $invoiceDate
     * @param BusinessFleet $businessFleet
     * @param int $newInvoiceNumber
     * @return string
     */
    private static function formatInvoiceNumber(\DateTime $invoiceDate, BusinessFleet $businessFleet, $newInvoiceNumber)
    {
        return $invoiceDate->format('Y').
        '/A'.
        $businessFleet->getId().
        sprintf("%'.08d", $newInvoiceNumber);
    }

    /**
     * Creates an invoice for a set of trips.
     *
     * @param Business $business
     * @param $invoiceNumber
     * @param BusinessTripPayment[] $tripPayments
     * @param $templateVersion
     * @param $amounts
     * @return BusinessInvoice
     */
    public static function createInvoiceForTrips(
        Business $business,
        $invoiceNumber,
        array $tripPayments,
        $templateVersion,
        $amounts
    ) {
        $invoiceDate = date_create();
        $formattedInvoiceNumber = self::formatInvoiceNumber($invoiceDate, $business->getFleet(), $invoiceNumber);
        $invoice = new BusinessInvoice(
            $business,
            $formattedInvoiceNumber,
            $templateVersion,
            self::TYPE_TRIP,
            $invoiceDate->format('Ymd'),
            $amounts
        );
        $total = 0;
        foreach ($tripPayments as $tripPayment) {
            $total += $tripPayment->getAmount();
        }

        $invoice->amount = $total;
        $body = [];

        foreach ($tripPayments as $key => $tripPayment) {
            $trip = $tripPayment->getBusinessTrip()->getTrip();
            /**
             * Changing the order, structure or content of the following
             * may interfere with $this->getInterval() function!
             * Test by running "export registries -d -c" from console
             */
            $body[] = [
                [$trip->getId()],
                [
                    'Inizio: ' . $trip->getTimestampBeginning()->format('d-m-Y H:i:s'),
                    'Fine: ' . $trip->getTimestampEnd()->format('d-m-Y H:i:s')],
                [
                    'Da: ' . $trip->getAddressBeginning(),
                    'A: ' . $trip->getAddressEnd()
                ],
                [$trip->getTripLengthInMin() . ' (min)'],
                [$trip->getCarPlate()],
                [$amounts['rows'][$key] . ' €']
            ];
        }
        $invoice->setContentBody([
            'greeting_message' => '',
            'contents' => [
                'header' => [
                    'ID',
                    'Data',
                    'Partenza / Arrivo',
                    'Durata',
                    'Targa',
                    'Totale'
                ],
                'body' => $body,
                'body-format' => [
                    'alignment' => [
                        'left',
                        'left',
                        'left',
                        'left',
                        'left',
                        'right'
                    ]
                ]
            ]
        ]);

        return $invoice;
    }

    /**
     * @param Business $business
     * @param $invoiceNumber
     * @param ExtraPayment[] $extraPayments
     * @param $templateVersion
     * @param array $amounts with fields grand_total_cents, grand_total, total, iva
     * @return BusinessInvoice
     */
    public static function createInvoiceForExtraOrPenalty(
        Business $business,
        $invoiceNumber,
        array $extraPayments,
        $templateVersion,
        $amounts
    ) {
        $invoiceDate = date_create();
        $formattedInvoiceNumber = self::formatInvoiceNumber($invoiceDate, $business->getFleet(), $invoiceNumber);
        $invoice = new BusinessInvoice(
            $business,
            $formattedInvoiceNumber,
            $templateVersion,
            self::TYPE_EXTRA,
            $invoiceDate->format('Ymd'),
            $amounts
        );
        $total = 0;
        foreach ($extraPayments as $extraPayment) {
            $total += $extraPayment->getAmount();
        }

        $invoice->amount = $total;
        $body = [];

        foreach ($extraPayments as $key => $extraPayment) {
            /**
             * Changing the order, structure or content of the following
             * may interfere with $this->getInterval() function!
             * Test by running "export registries -d -c" from console
             */
            $body[] = [
                [$extraPayment->getCreatedTs()->format('d-m-Y H:i:s')],
                [$extraPayment->getReason()],
                [$amounts['rows'][$key] . ' €']
            ];
        }


        $invoice->setContentBody([
            'greeting_message' => '<p>Nella pagina successiva troverà i dettagli del pagamento<br>' .
                'L\'importo totale della fattura è di EUR ' .
                $amounts['sum']['grand_total'] .
                '</p>',
            'contents' => [
                'header' => [
                    'Data',
                    'Causale',
                    'Totale'
                ],
                'body' => $body,
                'body-format' => [
                    'alignment' => [
                        'left',
                        'left',
                        'right'
                    ]
                ]
            ]
        ]);

        return $invoice;
    }

    /**
     * @return BusinessFleet
     */
    public function getFleet()
    {
        return $this->fleet;
    }

    /**
     * @return Business
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * @return Interval
     */
    public function getInterval()
    {
        /*
         * For invoices of type "TRIP" the interval is defined as:
         * - "start" the date of the beginning of the trip for
         *   the first tripPayment of the invoice
         * - "end" the date of the end of the trip for
         *   the last tripPayment of the invoice
         */
        if ($this->getType() == $this::TYPE_TRIP) {
            // Get the body with all the invoice rows
            $body = $this->getContent()['body']['contents']['body'];
            // Generate two starting dates to start comparing against
            $startDate = date_create_from_format("d-m-Y H:i:s", substr($body[0][1][0], 8));
            $endDate = date_create_from_format("d-m-Y H:i:s", substr($body[0][1][1], 6));
            // Compare all dates to find highest and lowest
            foreach ($body as $times) {
                $start = date_create_from_format("d-m-Y H:i:s", substr($times[1][0], 8));
                $end = date_create_from_format("d-m-Y H:i:s", substr($times[1][1], 6));
                // Compare start dates
                if ($start < $startDate) {
                    $startDate = $start;
                }
                // Compare end dates
                if ($end > $endDate) {
                    $endDate = $end;
                }
            }
            return new Interval($startDate, $endDate);

            /*
             * For invoices of type "FIRST_PAYMENT" and "PENALTY",
             * the interval is defined as:
             * - "start" the date of the invoice
             * - "end" the date of the invoice
             */
        } else {
            return new Interval($this->getDateTimeDate(), $this->getDateTimeDate());
        }
    }
    /**
     * @return \DateTime the value of invoiceDate converted to \DateTime
     */
    public function getDateTimeDate()
    {
        $date = $this->getInvoiceDate();
        $date = ($date % 100) . "/" . (floor(($date % 10000) / 100)) . "/" . floor($date / 10000);
        return date_create_from_format("d/m/Y", $date);
    }

    public function getTypeItalianTranslation()
    {
        switch ($this->getType()) {
            case self::TYPE_SUBSCRIPTION:
                return 'Iscrizione';
            case self::TYPE_TRIP:
                return 'Corse';
            case self::TYPE_EXTRA:
                return 'Extra';
            case self::TYPE_TIME_PACKAGE:
                return 'Pacchetto tempo';
        }
        return '';
    }

}
