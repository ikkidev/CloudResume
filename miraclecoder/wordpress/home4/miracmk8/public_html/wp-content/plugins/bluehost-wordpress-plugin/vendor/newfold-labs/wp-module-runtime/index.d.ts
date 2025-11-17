export interface DefaultSdk {
  wpversion: string;
}

type Capabilities =
  | "hasEcomdash"
  | "hasYithExtended"
  | "isEcommerce"
  | "isJarvis"
  | "canAccessHelpCenter"
  | "canAccessAI";

interface RuntimeSdk {
  hasCapability: (name: Capabilities) => boolean;
  adminUrl: (path: string) => string;
  createApiUrl: (path: string, qs?: Record<string, any>) => string;
  siteDetails: { url: string; title: string };
  sdk: DefaultSdk;
}

export const NewfoldRuntime: RuntimeSdk;
