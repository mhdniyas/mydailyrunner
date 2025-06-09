<!DOCTYPE html>
<html>
<head>
    <title>Invitation to Join Shop Stock & Financial Manager</title>
</head>
<body>
    <h1>You've Been Invited!</h1>
    
    <p>Hello {{ $user->name }},</p>
    
    <p>You have been invited to join the Shop Stock & Financial Manager application.</p>
    
    <p>Click the button below to accept the invitation and set up your account:</p>
    
    <p>
        <a href="{{ url('/accept-invitation/' . $token) }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px;">
            Accept Invitation
        </a>
    </p>
    
    <p>If you did not expect this invitation, please ignore this email.</p>
    
    <p>Thank you,<br>Shop Stock & Financial Manager Team</p>
</body>
</html>