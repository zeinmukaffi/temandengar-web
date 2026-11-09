
# TemanDengar - n8n Automation

Workflow automation untuk aplikasi TemanDengar.

## Environment

- n8n: Docker
- Backend: Laravel 12
- Email Provider: Gmail SMTP
- Authentication: Webhook Header Auth

## Workflow List

### WF-01 - New Consultation

File: `workflows/WF-01-New-Consultation.json`

Purpose:
- Send consultation confirmation to student.
- Send new consultation notification to counselor.
- Support preferred counselor selection.
- Use coordinator email as fallback recipient.

Trigger:
`consultation.requested`

### WF-02 - Schedule Management

File: `workflows/WF-02-Schedule-Management.json`

Purpose:
- Notify student when schedule is created.
- Notify counselor when schedule is created.
- Notify both parties when schedule is updated.
- Notify both parties when schedule is cancelled.

Events:
- consultation.scheduled
- consultation.rescheduled
- consultation.cancelled

## Credentials

Credentials must be configured separately in n8n.

Required:
- Gmail SMTP Credential
- Webhook Header Auth Credential

Never commit SMTP passwords, App Passwords,
webhook tokens, or other secrets.

## Import Instructions

1. Open n8n.
2. Create or open a workflow.
3. Import the corresponding JSON file.
4. Configure credentials.
5. Verify webhook URL and authentication.
6. Test the workflow.
7. Publish the workflow.

## Privacy

Standard email notification payloads must not include
full student consultation stories or unnecessary personal data.
