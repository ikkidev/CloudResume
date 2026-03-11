# Ikki Lachance

Senior Software Engineer | Platform Engineering | Healthcare Systems
United States | linkedin.com/in/ikkidev 

---

## Summary

Platform engineering leader with 10+ years designing and operating distributed systems in regulated healthcare and financial services environments. Architect of client connectivity platform serving 800+ global healthcare sites, owned end-to-end from Terraform infrastructure to CI/CD pipelines to operational runbooks. Known for cross-team technical leadership, eliminating systemic toil, and raising the engineering floor through mentorship, documentation, and tooling.

---

## Experience

### Intelerad
7 years total

**Tech Lead, Platform Services**
August 2024 to Present | New York, NY

- Brought 800+ global healthcare sites to 99.9% network uptime by redesigning the client connectivity platform on AWS ECS, replacing a brittle on-premises VPN fleet with a self-healing, fault-tolerant architecture that eliminated the class of incidents causing site outages.
- Unlocked continuous security patching across all platform services by completing the Python 2 to Python 3 migration, replacing a model where an unsupported runtime blocked automated vulnerability scanning and OS patch management.
- Kept multi-quarter initiatives moving without ambiguity by owning architecture decisions end-to-end, from decomposing roadmap work into trackable epics to unblocking cross-team dependencies with SRE, QA, and product.
- Raised the engineering ceiling of the team by mentoring engineers through architecture reviews, pair programming, and structured knowledge transfer, reducing dependence on senior-level involvement for day-to-day decisions.

**Senior Software Engineer, SRE**
January 2021 to August 2024 | Montreal, QC

- Modernized the core healthcare platform from on-premises to AWS ECS, establishing Terraform infrastructure across dev, staging, and production environments and migrating the primary database to PostgreSQL, cutting software release operations from multi-day per-site work to hours across 800+ global clients and 10,000+ servers.
- Raised first-attempt deployment success rates from 50% to 80% across 800+ client sites by building LCM Precheck 2.0, an automated pre-deployment validation platform with a REST API that surfaces connectivity and service dependency failures a week before scheduled maintenance windows ΓÇö eliminating wasted client downtime slots and the rescheduling of ops and support teams each failure required.
- Eliminated capacity constraints on concurrent client deployments by migrating the Ansible automation platform from a self-managed server to Kubernetes on AWS, enabling job workers to scale on demand across the entire client fleet.
- Hardened the security posture of a HIPAA-adjacent cloud environment by centralizing secrets management with HashiCorp Vault and AWS SSM and implementing TLS across the internal messaging bus, eliminating both ad-hoc credential sharing and an unencrypted internal data path.
- Cut per-build pipeline time by over 25% by restructuring Jenkins with multi-stage Docker builds, parallelized image pushes, and layer caching, compressing the feedback loop for every code change the team ships.

**Software Engineer, SRE**
April 2019 to January 2021 | Montreal, QC

- Established the continuous delivery foundation the team still operates on today by building the automated CI/CD pipelines that enabled software releases to reach healthcare clients worldwide without manual intervention.
- Gave the engineering organization a secure secrets management foundation from day one by building the HashiCorp Vault platform from scratch, including Terraform modules for dynamic database credentials, transit encryption, and AWS authentication, and validating it with a Go integration test suite against a live cluster.
- Unblocked software delivery to clients upgrading to RHEL 8 by resolving OS-level compatibility issues across package management, SSH key generation, and Ansible automation during the CentOS 7 to RHEL 8 migration.

---

### Morgan Stanley
January 2017 to January 2019 | Toronto, ON
Software Developer (Consultant)

- Designed a low-latency data distribution system for Over-the-Counter (OTC) Derivatives regulatory reporting covering Interest Rates, Credit, FX, and Options, processing millions of transactions daily with sub-second delivery SLAs.
- Reduced trade reconciliation time from 5 days to under 1 hour by building an automated reconciliation system that also delivered a 90% improvement in accuracy over the prior manual process.
- Built ETL pipelines integrating heterogeneous data sources; query optimization produced a 50% reduction in database load time and a 60% improvement in pipeline throughput.
- Implemented an automated regression testing framework that reduced data quality escapes by 85% across the reporting pipeline.

---

### Sanofi Pasteur
September 2014 to August 2015 | Toronto, ON
Software Developer Intern

- Built a real-time Statistical Process Control (SPC) dashboard for manufacturing process monitoring that enabled proactive detection of quality excursions and supported pharmaceutical regulatory compliance requirements.
- Optimized database queries to achieve a 2x speedup in data retrieval and implemented automated alerting for manufacturing deviations.

---

## Skills

Cloud and Infrastructure: AWS (ECS, EFS, ECR, RDS PostgreSQL, SSM, IAM, CloudWatch, KMS), Terraform, HashiCorp Vault, OpenVPN

Automation and CI/CD: Ansible, AWX, Jenkins, Docker, Kubernetes (EKS)

Languages: Python 3, Go, Bash, SQL

Databases and Messaging: PostgreSQL, MongoDB, event-driven messaging architectures

Security and Compliance: PKI/TLS, KMS encryption, SSM Patch Manager, CVE remediation, HIPAA-adjacent system design

Engineering Practices: Distributed systems design, site reliability engineering, technical roadmap planning, cross-team coordination, engineering mentorship

---

## Education

University of Toronto
Bachelor of Applied Science (BASc), Computer Engineering
