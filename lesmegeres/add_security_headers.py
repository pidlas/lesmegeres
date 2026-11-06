#!/usr/bin/env python3
"""Legacy wrapper. Canonical script: scripts/add_security_headers.py"""
import os
import runpy

ROOT = os.path.dirname(os.path.abspath(__file__))
TARGET = os.path.join(ROOT, "scripts", "add_security_headers.py")
runpy.run_path(TARGET, run_name="__main__")
