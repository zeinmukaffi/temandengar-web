
# TemanDengar Integration Contract

Version: 1.0
Application: TemanDengar
Backend: Laravel 12
Database: MySQL

## 1. Architecture

- Laravel: Main application and business logic.
- MySQL: Primary data storage.
- n8n: Workflow automation.
- Hermes Agent: Internal counselor AI assistant.
- AI Provider: Language model used by Hermes.

## 2. n8n Integration

Direction: Laravel -> n8n

Transport: HTTPS POST / Webhook
Authentication: Header Authentication
Content-Type: application/json

### Event types

| Event | Description |
|---|---|
| consultation.requested | New consultation request |
| consultation.scheduled | Consultation scheduled |
| consultation.rescheduled | Schedule changed |
| consultation.cancelled | Schedule cancelled |
| consultation.completed | Session completed |
| consultation.feedback_received | Feedback submitted |

### Standard payload

{
  "schema_version": "1.0",
  "event_id": "UUID",
  "event_type": "consultation.requested",
  "source": "temandengar",
  "occurred_at": "ISO-8601",
  "data": {}
}

### Privacy rules

Never include the following in standard notification webhooks:

- Student name or alias
- Email
- Phone number
- Consultation description
- Feedback comments
- Meeting links
- Consultation location

Use ticket number and operational status only.

### Delivery rules

- Every event must have a unique event_id.
- n8n must handle duplicate event_id idempotently.
- Webhook credentials must not be hardcoded.
- Workflow errors must be recorded.
- Do not expose private student data in Telegram messages.

## 3. Hermes Integration

Direction: Laravel -> Hermes

Purpose:
- draft_response
- suggest_questions

### Request

{
  "schema_version": "1.0",
  "task": "draft_response",
  "language": "id",
  "context": {
    "topic": "academic",
    "student_message": "Sanitized consultation context"
  }
}

### Response

{
  "status": "success",
  "result": {
    "draft_response": "Draft response",
    "suggested_questions": [
      "Suggested question 1",
      "Suggested question 2"
    ]
  }
}

### Privacy and safety rules

- Require explicit AI processing consent.
- Exclude student identity and contact details.
- Do not use AI to diagnose.
- Counselor must review generated content.
- Never automatically send AI output to students.
- Record AI assistance activity.
- Use an authenticated internal endpoint.

## 4. Environment Configuration

N8N_ENABLED=false
N8N_WEBHOOK_URL=
N8N_WEBHOOK_TOKEN=

HERMES_ENABLED=false
HERMES_BASE_URL=
HERMES_API_TOKEN=

## 5. Implementation Status

- [x] Integration architecture defined.
- [x] n8n event contract defined.
- [x] Hermes request/response contract defined.
- [x] Environment configuration prepared.
- [ ] n8n workflow implementation.
- [ ] Hermes API implementation.
- [ ] End-to-end integration testing.
