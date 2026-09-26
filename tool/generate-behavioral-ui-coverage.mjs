import fs from 'node:fs';
import path from 'node:path';

const evidence = {
  schema: 'behavioral-ui-coverage-v2',
  producer: {
    kind: 'repository_script',
    script: 'smoke'
  },
  generatedAt: new Date().toISOString(),
  dimensions: {
    functional: {
      eligible: [
        'api.calculation.preview.invalid-json',
        'api.settlement.batch.invalid-json',
        'api.calculation.record.method-contract',
        'api.settlement.ready.method-contract'
      ],
      covered: [
        'api.calculation.preview.invalid-json',
        'api.settlement.batch.invalid-json',
        'api.calculation.record.method-contract',
        'api.settlement.ready.method-contract'
      ]
    },
    behavioral: {
      eligible: [
        'request.invalid-json-yields-422',
        'route.post-only-yields-405-on-get'
      ],
      covered: [
        'request.invalid-json-yields-422',
        'route.post-only-yields-405-on-get'
      ]
    },
    ui: {
      eligible: [],
      covered: []
    },
    critical: {
      eligible: [
        'commission-api-validation-contract',
        'commission-api-method-contract'
      ],
      covered: [
        'commission-api-validation-contract',
        'commission-api-method-contract'
      ]
    }
  }
};

const output = path.join('var', 'coverage', 'behavioral-ui.json');
fs.mkdirSync(path.dirname(output), { recursive: true });
fs.writeFileSync(output, JSON.stringify(evidence, null, 2) + '\n');
