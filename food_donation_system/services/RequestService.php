<?php
require_once __DIR__ . '/../repositories/RequestRepositoryInterface.php';

class RequestService
{

    private RequestRepositoryInterface $requestRepo;

    public function __construct(RequestRepositoryInterface $requestRepo)
    {
        $this->requestRepo = $requestRepo;
    }

    public function createRequest(int $ngoId, int $donationId): bool
    {
        return $this->requestRepo->create($ngoId, $donationId);
    }

    public function approveRequest(int $requestId): bool
    {
        try {
            $request = $this->requestRepo->findById($requestId);
            if (!$request) {
                throw new Exception("Request not found.");
            }

            $request->approve();
            return $this->requestRepo->save($request);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }


    public function rejectRequest(int $requestId): bool
    {
        try {
            $request = $this->requestRepo->findById($requestId);

            if (!$request) {
                throw new Exception("Request not found.");
            }

            $request->reject();
            return $this->requestRepo->save($request);
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }


    public function getPendingRequests(): array
    {
        if (method_exists($this->requestRepo, 'findPending')) {
            return $this->requestRepo->findPending();
        }
        return [];
    }

    public function getApprovedRequests(): array
    {
        if (method_exists($this->requestRepo, 'findApproved')) {
            return $this->requestRepo->findApproved();
        }
        return [];
    }

    public function getRequestsByNgo(int $ngoId): array
    {
        return $this->requestRepo->findByNgo($ngoId);
    }

    public function getAllRequests(): array
    {
        if (method_exists($this->requestRepo, 'findAll')) {
            $requests = $this->requestRepo->findAll();
            return $requests ?: [];
        }
        return [];
    }



    public function findRequestById(int $id): ?Request
    {
        return $this->requestRepo->findById($id);
    }

    public function saveRequest(Request $request): bool
    {
        return $this->requestRepo->save($request);
    }

    public function getRequestsWithDonationDetailsByNgo($ngoId): array
    {
        if (method_exists(
            $this->requestRepo,
            'findRequestsWithDonationDetailsByNgo'
        )) {
            return $this->requestRepo
                ->findRequestsWithDonationDetailsByNgo($ngoId);
        }
        return [];
    }
}
