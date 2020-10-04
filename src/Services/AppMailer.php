<?php declare (strict_types = 1);

/*
 * (c) MyPhys.io Inc <admin@kinetxx.com>
 */
namespace App\Services;

use App\Entity\User;
use App\Entity\UserVerificationUrl;
use App\Repository\UserRepository;
use App\Services\UrlService;
use Exception;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

/**
 * Class AppMailer
 */
class AppMailer
{
    // Button text
    const BTN_CONTACT_US      = 'Contact Us';
    const BTN_FORGOT_PASSWORD = 'Reset your password';
    const BTN_FORGOT_USERNAME = 'Show your username';
    const BTN_REGISTER        = 'Register';
    const BTN_SIGN_IN         = 'Sign In';

    // Urls
    const URL_CONTACT_US     = '/contactus';
    const URL_REGISTER_USER  = '/activateUser';
    const URL_RESET_PASSWORD = '/resetPassword';
    const URL_SIGN_IN        = '/signin';

    // Message Types
    const MGS_TYPE_NEW_USER_MSG_TYPE        = 1;
    const MGS_TYPE_NEW_MASTER_MSG_TYPE      = 2;
    const MGS_TYPE_FORGOT_PASSWORD_MSG_TYPE = 3;
    const MGS_TYPE_FORGOT_USERNAME_MSG_TYPE = 4;

    // subject text
    const SUBJECT_FORGOT_PASSWORD    = 'Reset your QuizBank password';
    const SUBJECT_FORGOT_USERNAME    = 'Your QuizBank username';
    const SUBJECT_WELCOME            = 'Welcome to the team!';
    const SUBJECT_UPDATED_PASSWORD   = 'Your password has been updated';

    // Email Users
    const EMAIL_SUPPORT      = 'susantamcacvrca@gmail.com';
    const EMAIL_SUPPORT_NAME = 'QuizBank Support';

    private string           $siteBaseUrl;
    private string           $dir;
    private int              $threshold;
    private UserRepository   $userRepository;
    private MailerInterface  $mailer;
    private SessionInterface $session;
    private UrlService       $urlService;
    private ?User            $adminUser;

    /**
     * AppMailer constructor.
     *
     * @param string           $siteBaseUrl
     * @param int              $mailerThreshold
     * @param UserRepository   $userRepository
     * @param MailerInterface  $mailer
     * @param SessionInterface $session
     * @param UrlService       $urlService
     */
    public function __construct(string $siteBaseUrl, int $mailerThreshold, UserRepository $userRepository, MailerInterface $mailer, SessionInterface $session, UrlService $urlService)
    {
        $this->dir            = __DIR__.'/../Resources/Mail/';

        $this->siteBaseUrl    = $siteBaseUrl;
        $this->mailer         = $mailer;
        $this->session        = $session;
        $this->threshold      = $mailerThreshold;
        $this->userRepository = $userRepository;
        $this->urlService     = $urlService;
        $this->adminUser      = $this->userRepository->findOneBy(['roles' => [User::USER_ROLE_ADMIN]]);
    }

    /**
     * @param string $subject
     * @param string $message
     *
     * @throws Exception
     */
    public function sendContactEmail(string $subject, string $message)
    {
        $this->sendEmail($this->adminUser, $subject, null, ['message' => $message]);
    }

    /**
     * @param User $toUser
     *
     * @throws Exception
     */
    public function sendNewUserEmail(User $toUser)
    {
        // Create new link
        $urlInfo = $this->urlService->createUserVerificationUrl($toUser, UserVerificationUrl::USER_VERIFICATION_URL_TYPE_REGISTER);

        // Send email
        $this->sendNewUserMessage($toUser, $urlInfo);
    }

    /**
     * @param User $user
     *
     * @throws Exception
     */
    public function sendUserInvite(User $user): void
    {
        // Create new link
        $urlInfo = $this->urlService->createUserVerificationUrl($user, UserVerificationUrl::USER_VERIFICATION_URL_TYPE_REGISTER);

        // Send email
        $this->sendNewUserMessage($user, $urlInfo);
    }

    /**
     * @param User $user
     *
     * @throws Exception
     */
    public function sendForgotPassword(User $user): void
    {
        // Generate Url
        $urlInfo = $this->urlService->createUserVerificationUrl(
            $user,
            UserVerificationUrl::USER_VERIFICATION_URL_TYPE_FORGOT_PASSWORD
        );

        $a = array(
            'emailActionUrl' => $this->siteBaseUrl.self::URL_RESET_PASSWORD.'/'.$urlInfo->getVerificationKey(),
            'emailBody' => 'You recently requested to reset your password for your QuizBank account. Use the button below to reset it. ',
            'emailButtonText' => self::BTN_FORGOT_PASSWORD,
            'emailHeader' => 'Use this link to reset your password. The link is valid for '.$urlInfo->getIntervalString(),
            'emailIgnoreLink' => 'If you did not request a password reset, ',
            'emailLinkStatus' => 'This password reset is valid for '.$urlInfo->getIntervalString(),
            'emailTitle' => 'Set up a new password for QuizBank',
        );

        $this->sendMessage($user, self::MGS_TYPE_FORGOT_PASSWORD_MSG_TYPE, self::SUBJECT_FORGOT_PASSWORD, $a);
    }

    /**
     * @param User $user
     *
     * @throws Exception
     */
    public function sendForgotUsername(User $user): void
    {
        // Generate Url
        $urlInfo = $this->urlService->createUserVerificationUrl(
            $user,
            UserVerificationUrl::USER_VERIFICATION_URL_TYPE_FORGOT_USERNAME
        );

        $a = array(
            'emailActionUrl'  => $this->siteBaseUrl.self::URL_SIGN_IN.'?username='.$urlInfo->getVerificationKey(),
            'emailBody'       => 'You recently requested your username for your QuizBank account. Use the button below to show it.',
            'emailButtonText' => self::BTN_FORGOT_USERNAME,
            'emailHeader'     => 'Use this link to show your username. The link is valid for '.$urlInfo->getIntervalString(),
            'emailIgnoreLink' => 'If you did not request your username, ',
            'emailLinkStatus' => 'This show username link is valid for '.$urlInfo->getIntervalString(),
            'emailTitle'      => 'Show username for MyPhys.io',
        );

        $this->sendMessage($user, self::MGS_TYPE_FORGOT_USERNAME_MSG_TYPE, self::SUBJECT_FORGOT_USERNAME, $a);
    }

    /**
     * @param User $toUser
     *
     * @throws Exception
     */
    public function sendChangePasswordEmail(User $toUser)
    {
        $a = array(
            'emailActionUrl'  => $this->siteBaseUrl.self::URL_CONTACT_US.'?uid='.$toUser->getId(),
            'emailBody'       => "This email confirms that you have updated your password. If you did not make this change please contact QuizBank immediately.",
            'emailButtonText' => self::BTN_CONTACT_US,
            'emailHeader'     => 'Use this link to contact QuizBank Support.',
            'emailTitle'      => 'Password updated',
        );

        $this->sendMessage($toUser, self::MGS_TYPE_FORGOT_PASSWORD_MSG_TYPE, self::SUBJECT_UPDATED_PASSWORD, $a);
    }

    /**
     * @param User    $toUser   - Provider or Patient User added to the system
     * @param UrlInfo $arg      - urlLink
     *
     * @throws Exception
     */
    private function sendNewUserMessage(User $toUser, UrlInfo $arg)
    {
        $a = array('emailTitle' => 'Register with QuizBank');

        if ($toUser->isUser()) {
            $msgType  =  self::MGS_TYPE_NEW_USER_MSG_TYPE;
            $userType = 'user';
        } else {
            $msgType =  self::MGS_TYPE_NEW_MASTER_MSG_TYPE;
            $userType = 'master';
        }

        // New QuizBank User, needs to go through OnBoard/Register
        $a['emailActionUrl']  = $this->siteBaseUrl.self::URL_REGISTER_USER.'/'.$arg->getVerificationKey();
        $a['emailButtonText'] = self::BTN_REGISTER;
        $a['emailHeader']     = 'Use this link to register with QuizBank. The link is valid for '.$arg->getIntervalString();
        $a['emailLinkStatus'] = 'This registration link is valid for '.$arg->getIntervalString();
        $a['emailIgnoreLink'] = 'If you did request this registration link, ';
        $senderText           = 'You have been added ';

        $a['emailBody'] = sprintf(
            '%s as a %s at QuizBank. Welcome to the team! We\'ve got your back from here on out. Use the button below to register.',
            $senderText,
            $userType
        );

        $this->sendMessage($toUser, $msgType, self::SUBJECT_WELCOME, $a);
    }

    /**
     * @param User     $toUser
     * @param int      $msgType
     * @param string   $subject
     * @param array    $args
     *
     * @throws Exception
     */
    private function sendMessage(User $toUser, int $msgType, string $subject, array $args)
    {
        // Common to all messages
        $args['emailBaseUrl']    = $this->siteBaseUrl;
//        $args['emailLogo']       = $emailLogo;
        $args['emailSupportUrl'] = 'mailto:'.self::EMAIL_SUPPORT;
//        $args['emailLogoWidth']  = $practice->getLogoWidth();
//        $args['emailLogoHeight'] = Practice::PRACTICE_LOGO_DEFAULT_HEIGHT;

        /**
         * Given a User Id and Message Type we determine whether sufficient time has passed to warrant sending a
         * message of the same type.
         * The timestamp of the last sent message is stored in the Session as <UserId>:<Message Type>:<time>
         */
        switch ($msgType) {
            default:
                $key               = null;
                $sendMessage       = true;
                break;

            case self::MGS_TYPE_NEW_USER_MSG_TYPE:
                $key = "User:".$toUser->getId();
                $sendMessage = true;
                break;

            case self::MGS_TYPE_FORGOT_PASSWORD_MSG_TYPE:
            case self::MGS_TYPE_FORGOT_USERNAME_MSG_TYPE:
                // We mimic a new workout time for the patient, this means when the Provider creates the Workout
                // they will not receive a second email.
                $key = null;
                $sendMessage = true;
                break;
        }

//        if (self::MGS_TYPE_NEW_MASTER_MSG_TYPE === $msgType) {
//            $args['emailLogo'] = $emailLogo;
//        }

        if ($sendMessage) {
            $this->sendEmail($toUser, $subject, 'Email/emailTemplate.html.twig', $args);

            if ($key) {
                $this->session->set($key, time());
            }
        }
    }

    /**
     * @param User        $to
     * @param string      $subject
     * @param string|null $template
     * @param array       $context
     */
    private function sendEmail(User $to, string $subject, ?string $template, array $context)
    {
        $email = (new TemplatedEmail())
            ->from(new Address(self::EMAIL_SUPPORT, self::EMAIL_SUPPORT_NAME))
            ->to(new Address($to->getEmail(), $to->getFullName()))
            ->subject($subject)
            ->context($context);

        if ($template) {
            $email->htmlTemplate($template);
        } else {
            $email->html($context['message']);
        }

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // MAS TODO: Report Error
            // dump("The email was not sent. Error message:".$e->getMessage());
        }
    }
}
