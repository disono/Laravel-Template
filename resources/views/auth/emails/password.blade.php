{{--
 * Author: Archie, Disono (webmonsph@gmail.com)
 * Website: www.webmons.com
 * License: Apache 2.0
--}}
Click here to reset your password:
<a href="{{ $link = url('password/reset', $token) . '?email=' . urlencode($user->getEmailForPasswordReset()) }}">
    {{ $link }} </a>