<?php
echo password_verify('admin123', '$2y$10$Z7JHjvR0ZxN3YzZzQzOX9Cq1zZp4m3z9E0u1m5Pz9MZlQW') ? "MATCH" : "NO MATCH";
