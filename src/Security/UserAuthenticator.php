<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UserAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    private $userRepository; // Deprecated dynamic property

    public function __construct(UserRepository $userRepository,private UrlGeneratorInterface $urlGenerator)
    {
        $this->userRepository = $userRepository;

    }

    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $password = $request->request->get('password', '');
    
        $user = $this->userRepository->searchByEmail2($email);
    
        if (!$user) {
            throw new UsernameNotFoundException('User not found');
        }
    
        if ($user->isBlocked()) {
            throw new CustomUserMessageAuthenticationException('Your account has been blocked. Please contact the administrator for assistance.');
        }
    
        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($password),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }
    

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Check if the user is an admin
        $user = $token->getUser();
        $roles = $user->getRoles();
        
        if (in_array("ROLE_ADMIN", $roles, true)) {
            // Return a redirect response for admin
            return new RedirectResponse($this->urlGenerator->generate('adminDashboard'));
        } elseif (in_array("ROLE_USER", $roles, true)) {
            // Return a redirect response for user dashboard
            // Assuming 'UserDashboard' requires the user ID as a route parameter
            return new RedirectResponse($this->urlGenerator->generate('UserDashboard', ['id' => $user->getId()]));
        }
    
        // If user has neither role, redirect to some default route
        return new RedirectResponse($this->urlGenerator->generate('UserDashboard2'));
    }
    


    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    public function loadUserByUsername(string $username): UserInterface
    {
        $user = $this->userRepository->findOneByUsername($username);

        if (!$user) {
            throw new UsernameNotFoundException('User not found');
        }

        if ($user->isLocked()) {
            throw new LockedException('User is locked');
        }

        return $user;
    }






}
