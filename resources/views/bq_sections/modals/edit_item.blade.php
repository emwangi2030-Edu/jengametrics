        <!-- Edit Item Modal -->
                    <div class="modal fade" id="editItemModal{{ $item->id }}" tabindex="-1" aria-labelledby="editItemModalLabel{{ $item->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editItemModalLabel{{ $item->id }}">Edit Item</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('bq_items.update') }}" method="POST">
                                        @csrf
                                   

                                        <input type="hidden" name="id" value="{{ $item->id }}">
                                        <div class="form-group">
                                            <label for="editItemNameInput{{ $item->id }}">Quantity</label>
                                            <input type="text" class="form-control" id="editItemNameInput{{ $item->id }}" name="quantity" value="{{ $item->quantity }}" required>
                                        </div>


                                             <div class="form-group">
                                            <label for="editItemNameInput{{ $item->id }}">Rate</label>
                                            <input type="text" class="form-control" id="editItemNameInput{{ $item->id }}" name="rate" value="{{ $item->rate }}" required>
                                        </div>
                                  

                         
                                        <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>